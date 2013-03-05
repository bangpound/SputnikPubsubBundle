<?php

namespace Sputnik\Bundle\PubsubBundle\Tests\Hub;

use Sputnik\Bundle\PubsubBundle\Hub\HubProvider;
use Sputnik\Bundle\PubsubBundle\Hub\Hub;

class HubProviderTest extends \PHPUnit_Framework_TestCase
{
    private $hubA;
    private $hubB;
    private $hubC;

    public function setUp()
    {
        $this->hubA = new Hub('hubA', 'http://hub.a');
        $this->hubB = new Hub('hubB', 'http://hub.b');
        $this->hubC = new Hub('hubC', 'http://hub.c');
    }

    public function testInstantiation()
    {
        $provider = new HubProvider();
        $this->assertInstanceOf('Sputnik\\Bundle\\PubsubBundle\\Hub\\HubProviderInterface', $provider);
    }

    public function testAddingHubsViaConstructor()
    {
        $provider = new HubProvider(array($this->hubA, $this->hubB));
        $this->assertSame($provider->getHubs(), array($this->hubA, $this->hubB));
    }

    public function testHubsAssignmentAndRetrieval()
    {
        $provider = new HubProvider();

        $provider->addHub($this->hubA);
        $provider->addHub($this->hubC);
        $this->assertSame($provider->getHubs(), array($this->hubA, $this->hubC));

        $provider->addHub($this->hubB);
        $this->assertSame($provider->getHubs(), array($this->hubA, $this->hubC, $this->hubB));

        $provider->removeHub('hubA');
        $this->assertSame($provider->getHubs(), array($this->hubC, $this->hubB));

        try {
            $provider->removeHub('hubA');
            $this->fail('HubProvider::removeHub should throw an exception.');
        } catch (\Exception $e) {
            $this->assertInstanceOf('InvalidArgumentException', $e);
        }

        try {
            $provider->addHub($this->hubC);
            $this->fail('HubProvider::addHub should throw an exception.');
        } catch (\Exception $e) {
            $this->assertInstanceOf('InvalidArgumentException', $e);
        }

        try {
            $provider->getHub('hubA');
            $this->fail('HubProvider::addHub should throw an exception.');
        } catch (\Exception $e) {
            $this->assertInstanceOf('InvalidArgumentException', $e);
        }

        $this->assertSame($this->hubB, $provider->getHub('hubB'));
        $this->assertSame($this->hubC, $provider->getHub('hubC'));
    }
}
