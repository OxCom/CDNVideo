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
     *
     * @return bool|mixed
     */
    public function flush($file = null)
    {
        if (empty($file)) {
            $result = $this->_api->cacheFlushAll();
        } else {
            $result = $this->_api->cacheFlushFile($file);
        }

        return $result;
    }

    public function getCacheTime()
    {
        if (\CDNVideo\Tools\Utils::update_cache_required($this->_settings->getCacheInitTime(), $this->_settings->getCacheTTL())) {
            return $this->_settings->getNextInitTime();
        }

        return $this->_settings->getCacheInitTime();
    }

    /**
     * @TODO: search details about element in cache
     */
    public function search()
    {

    }
}