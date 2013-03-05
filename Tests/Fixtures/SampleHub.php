<?php

namespace Sputnik\Bundle\PubsubBundle\Tests\Fixtures;

use Sputnik\Bundle\PubsubBundle\Hub\Hub;

class SampleHub extends Hub
{
    public function __construct()
    {
        parent::__construct('sample', 'http://sample.hub.com', array('foo' => 'bar', 'baz' => 'qux'));
    }
}
