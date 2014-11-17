<?php

namespace CDNVideo\Tools;

class Parser
{
    /**
     * @var Settings
     */
    private   $_settings;
    protected $html = '';

    /**
     * So for HTML4 we've got:
     *      <a href=url>
     *      <applet codebase=url>
     *      <area href=url>
     *      <base href=url>
     *      <blockquote cite=url>
     *      <body background=url>
     *      <del cite=url>
     *      <form action=url>
     *      <frame longdesc=url> and <frame src=url>
     *      <head profile=url>
     *      <iframe longdesc=url> and <iframe src=url>
     *      <img longdesc=url> and <img src=url> and <img usemap=url>
     *      <input src=url> and <input usemap=url>
     *      <ins cite=url>
     *      <link href=url>
     *      <object classid=url> and <object codebase=url> and <object data=url> and <object usemap=url>
     *      <q cite=url>
     *      <script src=url>
     *
     * HTML 5 adds a few (and HTML5 seems to not use some of the ones above as well):
     *
     *      <audio src=url>
     *      <button formaction=url>
     *      <command icon=url>
     *      <embed src=url>
     *      <html manifest=url>
     *      <input formaction=url>
     *      <source src=url>
     *      <video poster=url> and <video src=url>
     *
     * These aren't necessarily simple URLs:
     *      <object archive=url> or <object archive="url1 url2 url3">
     *      <applet archive=url> or <applet archive=url1,url2,url3>
     *      <meta http-equiv="refresh" content="seconds; url">
     *
     * In addition, the style attribute can contain css declarations with one or several urls. For example:
     *      <div style="background: url(image.png)">
     *
     * @var array
     */
    private $_targets = array();

    public function __construct(\CDNVideo\Tools\Settings $settings)
    {
        $this->_settings = $settings;
        $this->_targets  = $settings->getTargets();
    }

    /**
     * Switch links in HTML to CDN links
     * @param $html
     *
     * @return string
     */
    public function process($html)
    {
        $this->html = $html;

        $this->parseTargets();

        return $this->html;
    }

    /**
     * Process links that were found
     */
    protected function parseTargets()
    {
        $targets = implode('|', $this->_targets);
        preg_match_all('/="([^"]*\.(' . $targets . '))"/i', $this->html, $result);

        // all links should be in second group
        $links = empty($result[1]) ? array() : $result[1];

        foreach ($links as $link) {
            $newLink = $this->makeLink($link);
            $this->html = str_replace($link, $newLink, $this->html);
        }
    }

    /**
     * Make link that pointed to CDNVideo
     *
     * @param string $link
     *
     * @return string
     */
    public function makeLink($link)
    {
        $link = trim($link);

        if (!in_array(pathinfo($link, PATHINFO_EXTENSION), $this->_targets)) {
            return $link;
        }

        if (empty($link)) {
            return '';
        }

        $details           = $this->_mb_parse_url($link);
        $details['scheme'] = empty($details['scheme']) ? 'http' : $details['scheme'];
        $details['host']   = $this->_settings->getCDNHost();

        if (isset($details['path'])) {
            $details['path'] = $details['path'][0] === '/' ? $details['path'] : ('/' . $details['path']);
        }

        return $this->_unparse_url($details);
    }

    /**
     * UTF-8 aware parse_url() replacement.
     *
     * @return array
     */
    private function _mb_parse_url($url)
    {
        $enc_url = preg_replace_callback(
            '%[^:/@?&=#]+%usD',
            function ($matches) {
                return urlencode($matches[0]);
            },
            $url
        );

        $parts = parse_url($enc_url);

        if ($parts === false) {
            throw new \InvalidArgumentException('Malformed URL: ' . $url);
        }

        foreach ($parts as $name => $value) {
            $parts[$name] = urldecode($value);
        }

        return $parts;
    }

    /**
     * Return back correct url
     *
     * @param $parsed_url
     *
     * @return string
     */
    private function _unparse_url($parsed_url)
    {
        $scheme   = isset($parsed_url['scheme']) ? $parsed_url['scheme'] . '://' : '';
        $host     = isset($parsed_url['host']) ? $parsed_url['host'] : '';
        $port     = isset($parsed_url['port']) ? ':' . $parsed_url['port'] : '';
        $user     = isset($parsed_url['user']) ? $parsed_url['user'] : '';
        $pass     = isset($parsed_url['pass']) ? ':' . $parsed_url['pass'] : '';
        $pass     = ($user || $pass) ? "$pass@" : '';
        $path     = isset($parsed_url['path']) ? $parsed_url['path'] : '';
        $query    = isset($parsed_url['query']) ? '?' . $parsed_url['query'] : '';
        $fragment = isset($parsed_url['fragment']) ? '#' . $parsed_url['fragment'] : '';

        return "$scheme$user$pass$host$port$path$query$fragment";
    }
}