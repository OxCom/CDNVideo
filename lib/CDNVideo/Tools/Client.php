<?php

namespace CDNVideo\Tools;

class Client
{
    private $_settings;

    public function __construct(\CDNVideo\Tools\Settings $settings)
    {
        $this->_settings = $settings;
    }

    /**
     * Make a request for API
     *
     * @param array $params
     */
    public function call($url)
    {

    }
}