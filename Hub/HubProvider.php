<?php

namespace Sputnik\Bundle\PubsubBundle\Hub;

/**
 * @package SputnikPubsubBundle_Hub
 */
class HubProvider implements HubProviderInterface
{
    private $hubs = array();

    /**
     * @param array $hubs
     */
    public function __construct($hubs = array())
    {
        foreach ($hubs as $hub) {
            $this->addHub($hub);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function addHub(HubInterface $hub)
    {
        if (isset($this->hubs[$hub->getName()])) {
            throw new \RuntimeException(sprintf('hub "%s" is already registered', $hub->getName()));
        }

        $this->hubs[$hub->getName()] = $hub;
    }

    /**
     * {@inheritdoc}
     */
    public function getHub($name)
    {
        if (!isset($this->hubs[$name])) {
            throw new \InvalidArgumentException(sprintf('hub "%s" not registered', $name));
        }

        return $this->hubs[$name];
    }

    /**
     * {@inheritdoc}
     */
    public function removeHub($name)
    {
        if (!isset($this->hubs[$name])) {
            throw new \InvalidArgumentException(sprintf('hub "%s" not registered', $name));
        }

        unset($this->hubs[$name]);
    }

    /**
     * @return array
     */
    public function getHubs()
    {
        return $this->hubs;
    }
}
