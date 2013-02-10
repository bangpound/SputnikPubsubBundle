<?php

namespace Sputnik\Bundle\PubsubBundle\Hub;

/**
 * @package SputnikPubsubBundle_Hub
 */
interface HubProviderInterface
{
    /**
     * @param HubInterface $hub
     *
     * @throws \RuntimeException
     */
    public function addHub(HubInterface $hub);

    /**
     * @param string $name
     *
     * @return HubInterface
     *
     * @throws \InvalidArgumentException
     */
    public function getHub($name);

    /**
     * @param string $name
     *
     * @throws \InvalidArgumentException
     */
    public function removeHub($name);

    /**
     * @return array
     */
    public function getHubs();
}
