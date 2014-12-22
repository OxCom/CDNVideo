<?php
namespace CDNVideo;

class CDNChain
{

    /**
     * List of chain options
     *
     * @var array
     */
    protected $settings;

    public function __construct($settings = array())
    {
        $this->settings = $settings;
    }

    /**
     * Add element to chain
     *
     * @param array $options
     */
    public function addChainElement($options = array())
    {
        $this->settings[] = $options;
        return $this;
    }

    /**
     * Process html by chain
     * @param $html
     *
     * @return string
     */
    public function process($html)
    {
        foreach ($this->settings as $options)
        {
            $CDNVideo = new \CDNVideo\CDNVideo($options);
            $html = $CDNVideo->process($html);
        }

        return $html;
    }
}