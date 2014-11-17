<?php

namespace CDNVideo\Tools;

class Api
{
    private $_url = 'http://api.cdnvideo.ru:8888/';
    private $_settings;
    private $_client;

    public function __construct(\CDNVideo\Tools\Settings $settings)
    {
        $this->_settings = $settings;
        $this->_client   = new \CDNVideo\Tools\Client($this->_settings);
    }

    public function cacheFlushAll()
    {

    }

    public function cacheFlushFile($file)
    {

    }
}