<?php

namespace Sputnik\Bundle\PubsubBundle\Hub;

class SuperfeedrHub extends Hub
{
    private $username;
    private $password;

    /**
     * @param string $name
     * @param string $url
     * @param string $username
     * @param string $password
     * @param array  $params
     */
    public function __construct($name, $url, $username, $password, array $params = array())
    {
        parent::__construct($name, $url, $params);

        $this->username = $username;
        $this->password = $password;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getPassword()
    {
        return $this->password;
    }
}
