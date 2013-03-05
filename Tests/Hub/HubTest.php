<?php

namespace Sputnik\Bundle\PubsubBundle\Tests\Hub;

use Sputnik\Bundle\PubsubBundle\Hub\Hub;

class HubTest extends \PHPUnit_Framework_TestCase
{
    public function testInstantiationAndGetters()
    {
        $hub = new Hub('blogger', 'http://pubsubhubbub.appspot.com', array('foo' => 'bar', 'baz' => 'qux'));
        $this->assertInstanceOf('Sputnik\\Bundle\\PubsubBundle\\Hub\\HubInterface', $hub);

        $this->assertEquals('blogger', $hub->getName());
        $this->assertEquals('http://pubsubhubbub.appspot.com', $hub->getUrl());
        $this->assertEquals(array('foo' => 'bar', 'baz' => 'qux'), $hub->getRequestParams());
    }
}
