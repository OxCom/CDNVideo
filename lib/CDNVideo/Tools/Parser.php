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
        $regexp = '/((?<=[^style]=["|\'])([^"|\']*\.(' . $targets . ')[^"|\']*)|(?<=url[\(])(([^\)])*(' . $targets . ')[^\)]*))/i';
        preg_match_all($regexp, $this->html, $result);

        // all links should be in second group
        $links = empty($result[1]) ? array() : $result[1];

        $links = array_unique($links);
        foreach ($links as $link) {
            // remove ' and " from URL
            $link       = str_replace(array('\'', '"'), '', $link);
            $newLink    = $this->makeLink($link);
            $link       = preg_quote($link, '/');

            // (?<=url[\(])(\/sdah\/styles\.css)(?=[\)])|(?<=url\([\"|\'])(\/sdah\/styles\.css)(?=[\"|\'])

            $this->html = preg_replace("/((?<=[^style]=[\"|\'])({$link})(?=[\"|\'])|((?<=url[\(])({$link})(?=[\)])|(?<=url\([\"|\'])({$link})(?=[\"|\'])))/i", $newLink, $this->html);
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
        $link      = trim($link);
        $linkClear = preg_replace('/(.*)\?(.*)/', '$1', $link);

        if (!in_array(pathinfo($linkClear, PATHINFO_EXTENSION), $this->_targets)) {
            return $link;
        }

        if (empty($link)) {
            return '';
        }

        if (\CDNVideo\Tools\Utils::update_cache_required($this->_settings->getCacheInitTime(), $this->_settings->getCacheTTL())) {
            $link = \CDNVideo\Tools\Utils::update_link_cache($link, $this->_settings->getNextInitTime());
        }

        $link = \CDNVideo\Tools\Utils::format_path($link, $this->_settings);
        return $link;
    }

    public function getNextInitTime()
    {
        return $this->_settings->getNextInitTime();
    }
}