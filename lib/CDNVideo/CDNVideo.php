<?php

namespace CDNVideo;

class CDNVideo
{
    /**
     * @var Tools\Settings
     */
    private $_settings;

    /**
     * @var Tools\Parser
     */
    private $_parser;

    /**
     * @var Tools\Api
     */
    private $_api;

    /**
     * @param array $settings
     */
    public function __construct(array $settings)
    {
        $this->_settings = new \CDNVideo\Tools\Settings($settings);
        $this->_parser   = new \CDNVideo\Tools\Parser($this->_settings);
        $this->_api      = new \CDNVideo\Tools\Api($this->_settings);
    }

    /**
     * Parse HTML and replace switch all links to CDNVideo
     *
     * @param string $html
     *
     * @return string
     */
    public function process($html)
    {
        return $this->_parser->process($html);
    }

    /**
     * Switch single link to CDNVideo
     *
     * @param string $link
     *
     * @return string
     */
    public function processLink($link)
    {
        return $this->_parser->makeLink($link);
    }

    /**
     * Clear cache all cache of cache for single file
     *
     * @param string|null $file
     */
    public function flush($file = null)
    {
        if (empty($file)) {
            $this->_api->cacheFlushAll();
        } else {
            $this->_api->cacheFlushFile($file);
        }
    }

    /**
     * @TODO: search details about element in cache
     */
    public function search()
    {

    }
}