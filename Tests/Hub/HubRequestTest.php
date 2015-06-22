<?php

namespace Sputnik\Bundle\PubsubBundle\Tests\Hub;

use Sputnik\Bundle\PubsubBundle\Tests\Fixtures\SampleHub;
use Sputnik\Bundle\PubsubBundle\Tests\Fixtures\SampleTopic;
use Sputnik\Bundle\PubsubBundle\Hub\HubSubscriberInterface;
use Sputnik\Bundle\PubsubBundle\Hub\HubRequest;

class HubRequestTest extends \PHPUnit_Framework_TestCase
{
    private $generator;
    private $httpClient;

    public function setUp()
    {
        $this->generator = $this->getMockBuilder('Symfony\\Component\\Routing\\Generator\\UrlGeneratorInterface')->getMock();
        $this->httpClient = $this->getMockBuilder('GuzzleHttp\\Client')->setMethods(array('request'))->getMock();
    }

    public function getHubSubscriptionModes()
    {
        return array(
            array(HubSubscriberInterface::SUBSCRIBE),
            array(HubSubscriberInterface::UNSUBSCRIBE)
        );
    }

    /**
     * @dataProvider getHubSubscriptionModes()
     */
    public function testSubscribe($mode)
    {
        $hub = new SampleHub();
        $topic = new SampleTopic();

        $request = $this->getHubRequest('callback_route');

        // URL generator returns below URL as a callback using specified route.
        $this->generator
            ->expects($this->once())
            ->method('generate')
            ->with($this->equalTo('callback_route'))
            ->will($this->returnValue('http://my-callback-url'))
        ;

        // POST params to send: regular hub.* values + parameters unique to hub.
        $parameters = array_merge(array(
            'hub.mode'     => $mode,
            'hub.verify'   => 'sync',
            'hub.callback' => 'http://my-callback-url',
            'hub.topic'    => $topic->getTopicUrl(),
            'hub.secret'   => $topic->getTopicSecret()
        ), $hub->getRequestParams());

        $this->assertPostToUrlReturns($hub->getUrl(), $parameters, 204);

        $result = $request->sendRequest($mode, $topic, $hub);
        $this->assertTrue($result);
    }

    /**
     * @param string $callbackRoute
     * @param string $testRoute
     *
     * @return HubRequest
     */
    private function getHubRequest($callbackRoute, $testRoute = null)
    {
        $logger = $this->getMockBuilder('Psr\\Log\\LoggerInterface')->getMock();

        return new HubRequest($this->generator, $callbackRoute, $this->httpClient, $logger, $testRoute);
    }

    /**
     * @param string  $url
     * @param array   $params
     * @param integer $code
     */
    private function assertPostToUrlReturns($url, array $params, $code)
    {
        $response = $this->getMockBuilder('GuzzleHttp\\Psr7\\Response')
            ->disableOriginalConstructor()
            ->setMethods(array('getStatusCode'))
            ->getMock()
        ;

        $this->httpClient
            ->expects($this->once())
            ->method('request')
            ->with('post', $this->equalTo($url), array(
                'form_params' => $params,
                'auth' => ['username', 'password']
            ))
            ->will($this->returnValue($response))
        ;

        $response
            ->expects($this->once())
            ->method('getStatusCode')
            ->will($this->returnValue($code))
        ;
    }
}
