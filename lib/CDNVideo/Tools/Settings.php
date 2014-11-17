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

    public function __construct(array $settings)
    {
        $this->_domain  = empty($settings['domain']) ? '' : $settings['domain'];
        $this->_targets = empty($settings['targets']) ? $this->_targets : $settings['targets'];
        $this->_id      = empty($settings['id']) ? '' : $settings['id'];
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

    public function getCDNHost()
    {
        return $this->_domain;
    }
}