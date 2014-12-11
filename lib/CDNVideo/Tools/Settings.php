<?php

namespace CDNVideo\Tools;

class Settings
{
    /**
     * Domain name at CDNVideo
     *
     * @var string
     */
    private $_domain = '';

    /**
     * List of file extensions that should be cached on CDNVideo.
     *
     * @var array
     */
    private $_targets = array(
        'css', 'js', 'jpeg', 'jpg', 'png', 'gif', 'mp3', 'mp4', 'ogg', 'flv'
    );

    /**
     * ID that should be given from CDNVideo to API access
     *
     * @var string
     */
    private $_id = '';

    /**
     * Cache time period: 14 * 24 * 60 * 60 = 1209600
     * @var int
     */
    private $_cacheTTL = 1209600;

    /**
     * Time when cache was initialized
     * @var int
     */
    private $_cacheInitTime = 0;

    public function __construct(array $settings)
    {
        $this->_domain        = \CDNVideo\Tools\Utils::val($settings, 'domain', '');
        $this->_targets       = \CDNVideo\Tools\Utils::val($settings, 'targets', $this->_targets);
        $this->_id            = \CDNVideo\Tools\Utils::val($settings, 'id', '');
        $this->_cacheTTL      = \CDNVideo\Tools\Utils::val($settings, 'ttl', 1209600);
        $this->_cacheInitTime = \CDNVideo\Tools\Utils::val($settings, 'init_time', time());
    }

    public function getId()
    {
        return $this->_id;
    }

    /**
     * Return target extensions that should be processed
     *
     * @return array
     */
    public function getTargets()
    {
        return $this->_targets;
    }

    /**
     * Return CDN host
     *
     * @return string
     */
    public function getCDNHost()
    {
        return $this->_domain;
    }

    /**
     * Return host of current server
     * @return string
     */
    public function getLocalHost()
    {
        return $_SERVER['SERVER_NAME'];
    }

    /**
     * Return cache TTL time
     *
     * @return int
     */
    public function getCacheTTL()
    {
        return (int)$this->_cacheTTL;
    }

    /**
     * Get time when cache was initialized
     *
     * @return int
     */
    public function getCacheInitTime()
    {
        return (int)$this->_cacheInitTime;
    }

    /**
     * Return scheme and host of current server
     *
     * @return string
     */
    public function getLocalScheme()
    {
        return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443) ? "https" : "http";
    }
}