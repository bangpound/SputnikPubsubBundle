<?php

namespace Sputnik\Bundle\PubsubBundle\Hub;

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
     * @return HubInterface[]
     */
    public function getHubs();
}
