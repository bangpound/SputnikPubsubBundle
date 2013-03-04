<?php

namespace Sputnik\Bundle\PubsubBundle\Tests\Fixtures;

use Sputnik\Bundle\PubsubBundle\Hub\Hub;

/**
 * @package SputnikPubsubBundle_Tests
 * @subpackage Fixtures
 */
class SampleHub extends Hub
{
    public function __construct()
    {
        parent::__construct('sample', 'http://sample.hub.com', array('foo' => 'bar', 'baz' => 'qux'));
    }
}