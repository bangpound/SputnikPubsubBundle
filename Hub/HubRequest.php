<?php

namespace Sputnik\Bundle\PubsubBundle\Hub;

use Psr\Log\LoggerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Guzzle\Http\ClientInterface as HttpClient;
use Guzzle\Http\Exception\ClientErrorResponseException;
use Sputnik\Bundle\PubsubBundle\Model\TopicInterface;

class HubRequest
{
    private $urlGenerator;
    private $route;
    private $httpClient;
    private $logger;
    private $hubTestRoute;

    /**
     * @param UrlGeneratorInterface $urlGenerator
     * @param string                $route
     * @param HttpClient            $httpClient
     * @param LoggerInterface       $logger
     * @param string                $hubTestRoute
     */
    public function __construct(UrlGeneratorInterface $urlGenerator, $route, HttpClient $httpClient, LoggerInterface $logger, $hubTestRoute = null)
    {
        $this->urlGenerator = $urlGenerator;
        $this->route = $route;
        $this->httpClient = $httpClient;
        $this->logger = $logger;
        $this->hubTestRoute = $hubTestRoute;
    }

    /**
     * @param string         $mode
     * @param TopicInterface $topic
     * @param HubInterface   $hub
     *
     * @throws \Exception|\Guzzle\Http\Exception\ClientErrorResponseException
     *
     * @return boolean
     */
    public function sendRequest($mode, TopicInterface $topic, HubInterface $hub)
    {
        $params = array(
            'hub.mode'     => $mode,
            'hub.verify'   => 'sync',
            'hub.topic'    => $topic->getTopicUrl(),
            'hub.callback' => $this->urlGenerator->generate($this->route, array('id' => $topic->getId()), true),
            'hub.secret'   => $topic->getTopicSecret()
        );
        $params = array_merge($params, $hub->getRequestParams());

        if ($this->hubTestRoute) {
            $hubUrl = $this->urlGenerator->generate($this->hubTestRoute, array('name' => $hub->getName()), true);
        } else {
            $hubUrl = $hub->getUrl();
        }

        $request = $this->httpClient->post($hubUrl);
        if ($hub instanceof SuperfeedrHub) {
            $request->setAuth($hub->getUsername(), $hub->getPassword());
        }

        try {
            $response = $request->addPostFields($params)->send();
        } catch (ClientErrorResponseException $e) {
            if ($this->hubTestRoute) {
                throw $e;
            }
            $this->logger->warning('hub request: ' . $e->getMessage());

            return false;
        }

        return $response->getStatusCode() === 204;
    }
}
