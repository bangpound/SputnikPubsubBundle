<?php

namespace Sputnik\PubsubBundle\Controller;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Guzzle\Http\ClientInterface as HttpClient;
use Guzzle\Http\Exception\ClientErrorResponseException;

/**
 * Simple Hub implementation.
 *
 * @package SputnikPubsubBundle_Controller
 */
class HubController
{
    private $logger;
    private $httpClient;

    /**
     * @param LoggerInterface $logger
     * @param HttpClient      $httpClient
     */
    public function __construct(LoggerInterface $logger, HttpClient $httpClient)
    {
        $this->logger = $logger;
        $this->httpClient = $httpClient;
    }

    /**
     * Ping callback URL with relevant hub mode. Returns 204 if callback request was OK, 400 otherwise.
     *
     * @param Request $request
     * @param string  $name
     *
     * @return Response
     */
    public function process(Request $request, $name)
    {
        $debug = 'hub: ' . $name;
        foreach ($request->request as $key => $value) {
            $debug .= '; ' . $key . ' => ' . $value;
        }
        $this->logger->debug($debug);

        $challenge = uniqid();

        $mode = $request->get('hub_mode');
        $callback = $request->get('hub_callback');

        try {
            $response = $this->httpClient->get($callback . '?hub_mode=' . $mode . '&hub_challenge=' . $challenge)->send();
        } catch (ClientErrorResponseException $e) {
            $this->logger->error('hub: error getting confirmation from callback URL: ' . $callback);

            // Invalid request -> hub was not able to reach callback URL
            return new Response('', 400);
        }

        // Callback URL should always(!) return 200
        if ($response->getStatusCode() !== 200) {
            $code = 400;

        // Callback URL should return challange
        } elseif ((string) $response->getBody() !== $challenge) {
            $code = 400;

        // Callaback accepted.
        } else {
            $code = 204;
        }

        return new Response('', $code);
    }
}
