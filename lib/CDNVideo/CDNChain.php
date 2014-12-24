<?php
namespace CDNVideo;

class CDNChain
{
    /**
     *
     * @var array
     */
    protected $cacheChainTime = array();

    /**
     * List of chain elements
     *
     * @var \CDNVideo\CDNVideo[]
     */
    protected $chain = array();

    /**
     * Add element to chain
     *
     * @param array $options
     */
    public function addChainElement($options = array())
    {
        $key = \CDNVideo\Tools\Utils::val($options, 'chainKey', null);
        if (empty($key)) {
            $key = md5(serialize($options));
        }

        $chainElement      = new \CDNVideo\CDNVideo($options);
        $this->chain[$key] = $chainElement;
        $this->setCacheTime($key, $chainElement->getCacheTime());

        return $this;
    }

    /**
     * Process html by chain
     *
     * @param $html
     *
     * @return string
     */
    public function process($html)
    {
        foreach ($this->chain as $key => $CDNVideo) {
            $html = $CDNVideo->process($html);
            $this->setCacheTime($key, $CDNVideo->getCacheTime());
        }

        return $html;
    }

    /**
     * Store cache time for each chain element
     *
     * @param $key
     * @param $time
     *
     * @return $this
     */
    protected function setCacheTime($key, $time)
    {
        $this->cacheChainTime[$key] = $time;

        return $this;
    }

    /**
     * Get cache time by key. If key is empty all list will be return
     *
     * @param null $key
     *
     * @return array|string
     */
    public function getCacheTime($key = null)
    {
        if (empty($key)) {
            return $this->cacheChainTime;
        }

        return \CDNVideo\Tools\Utils::val($this->cacheChainTime, $key, 0);
    }
}