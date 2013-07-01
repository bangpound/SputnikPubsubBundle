<?php

namespace Sputnik\Bundle\PubsubBundle\Tests\Hub;

use Sputnik\Bundle\PubsubBundle\Hub\SuperfeedrHub;

class SuperfeedrHubTest extends \PHPUnit_Framework_TestCase
{
    public function testGetters()
    {
        $hub = new SuperfeedrHub('foo', 'http://url', 'username', 'password', array());

        $this->assertEquals('username', $hub->getUsername());
        $this->assertEquals('password', $hub->getPassword());
    }
}
