<?php

namespace Sputnik\Bundle\PubsubBundle\Tests\Fixtures;

use Sputnik\Bundle\PubsubBundle\Hub\SuperfeedrHub;

class SampleHub extends SuperfeedrHub
{
    public function __construct()
    {
        parent::__construct('sample', 'http://sample.hub.com', 'username', 'password', array('foo' => 'bar', 'baz' => 'qux'));
    }
}
