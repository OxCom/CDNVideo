<?php

namespace CDNVideo\Tools;

class Api
{
    private $_url = 'http://api.cdnvideo.ru:8888/';
    private $_settings;
    private $_client;

    const CONTENT_TYPE_HTTP = 'http';
    const METHOD_PURGE_FILE = '0/purge1';
    const METHOD_PURGE_ALL  = '0/purge';

    public function __construct(\CDNVideo\Tools\Settings $settings)
    {
        $this->_settings = $settings;
        $this->_client   = new \CDNVideo\Tools\Client($this->_settings);
    }

    /**
     * Purge all cache on CDN
     *
     * @return bool|mixed
     */
    public function cacheFlushAll()
    {
        $query = http_build_query(array(
            'id'     => $this->_settings->getId(),
            'type'   => self::CONTENT_TYPE_HTTP,
            'object' => '*',
        ));

        $url = $this->buildURL(self::METHOD_PURGE_ALL, array(
            'type'   => self::CONTENT_TYPE_HTTP,
            'object' => '*',
        ));

        return $this->_client->call($url);
    }

    /**
     * Clear cache for selected file
     *
     * @param $file
     *
     * @return bool|mixed
     */
    public function cacheFlushFile($file)
    {
        // file path should contain scheme part and host
        $file = \CDNVideo\Tools\Utils::format_path($file, $this->_settings->getLocalScheme(), $this->_settings->getLocalHost());

        $url = $this->buildURL(self::METHOD_PURGE_FILE, array(
            'type'   => self::CONTENT_TYPE_HTTP,
            'object' => $file,
        ));

        return $this->_client->call($url);
    }

    public function buildURL($method = '', $params = array())
    {
        $query = http_build_query(array_merge(array(
            'id'     => $this->_settings->getId(),
        ), $params));

        return $this->_url . $method . '?' . $query;
    }
}