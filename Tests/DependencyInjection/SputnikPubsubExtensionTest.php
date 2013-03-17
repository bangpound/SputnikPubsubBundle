<?php

namespace Sputnik\Bundle\PubsubBundle\Tests\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Sputnik\Bundle\PubsubBundle\DependencyInjection\SputnikPubsubExtension;

class SputnikPubsubExtensionTest extends \PHPUnit_Framework_TestCase
{
    private $container;

    public function setUp()
    {
        $this->container = new ContainerBuilder();
    }

    public function getDrivers()
    {
        return array(
            array('doctrine'),
            array('doctrine_mongo'),
            array('mandango'),
        );
    }

    public function testLoadWithEmptyConfigFallbacksToDefaultValues()
    {
        $config = array();

        $extension = new SputnikPubsubExtension();
        $extension->load(array($config), $this->container);

        $this->assertTrue(in_array('sputnik_pubsub.manager.topic', $this->container->getServiceIds()));
    }

    /**
     * @dataProvider getDrivers
     */
    public function testLoadWithValidDriver($driver)
    {
        $config = array('driver' => $driver);

        $extension = new SputnikPubsubExtension();
        $extension->load(array($config), $this->container);

        $this->assertTrue(in_array('sputnik_pubsub.manager.topic', $this->container->getServiceIds()));
    }

    /**
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function testLoadWithInvalidDriverThrowsException()
    {
        $config = array('driver' => 'invalid');

        $extension = new SputnikPubsubExtension();
        $extension->load(array($config), $this->container);
    }

    public function testLoadWithRoute()
    {
        $config = array('route' => 'my-callback-route');

        $extension = new SputnikPubsubExtension();
        $extension->load(array($config), $this->container);

        $this->assertEquals('my-callback-route', $this->container->getParameter('sputnik_pubsub.route'));
    }

    public function testLoadWithLiveHubDisabledByDefault()
    {
        $config = array('hub_test_route' => 'my-hub-route');

        $extension = new SputnikPubsubExtension();
        $extension->load(array($config), $this->container);

        $hubTestRoute = $this->container->getDefinition('sputnik_pubsub.hub.request')->getArgument(4);
        $this->assertEquals('my-hub-route', $hubTestRoute);
    }

    public function testLoadWithLiveHubEnabled()
    {
        $config = array('live_hub' => true);

        $extension = new SputnikPubsubExtension();
        $extension->load(array($config), $this->container);

        $hubTestRoute = $this->container->getDefinition('sputnik_pubsub.hub.request')->getArgument(4);
        $this->assertFalse((boolean) $hubTestRoute);
    }
}
