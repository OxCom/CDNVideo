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
     * @param $url
     *
     * @internal param array $params
     *
     * @return bool|mixed
     */
    public function call($url)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_USERAGENT      => 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/38.0.2125.111 Safari/537.36',
            CURLOPT_URL            => $url,
            CURLOPT_CONNECTTIMEOUT => 5,
            CURLOPT_TIMEOUT        => 5,
        ));

        $output = curl_exec($curl);
        $code   = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        return ($code >= 200 && $code < 300) ? $output : false;
    }
}