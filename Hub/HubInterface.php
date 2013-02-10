<?php

namespace Sputnik\Bundle\PubsubBundle\Hub;

/**
 * @package SputnikPubsubBundle_Hub
 */
interface HubInterface
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @return string
     */
    public function getUrl();

    /**
     * @return array
     */
    public function getRequestParams();
}
