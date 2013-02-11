<?php

namespace Sputnik\Bundle\PubsubBundle\Hub;

/**
 * @package SputnikPubsubBundle_Hub
 */
class Hub implements HubInterface
{
    private $name;
    private $url;
    private $params;

    /**
     * @param string $name
     * @param string $url
     * @param array  $params
     */
    public function __construct($name, $url, array $params = array())
    {
        $this->name = $name;
        $this->url = $url;
        $this->params = $params;
    }

    /**
     * {@inheritdoc}
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function getRequestParams()
    {
        return $this->params;
    }
}
