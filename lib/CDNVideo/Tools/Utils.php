<?php
namespace CDNVideo\Tools;

class Utils {
    /**
     * UTF-8 aware parse_url() replacement.
     *
     * @return array
     */
    static public function mb_parse_url($url)
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
    static public function unparse_url($parsed_url)
    {
        $scheme   = isset($parsed_url['scheme']) ? $parsed_url['scheme'] . '://' : '';
        $host     = \CDNVideo\Tools\Utils::val($parsed_url, 'host', '');
        $port     = \CDNVideo\Tools\Utils::val($parsed_url, 'port', '');
        $user     = \CDNVideo\Tools\Utils::val($parsed_url, 'user', '');
        $pass     = \CDNVideo\Tools\Utils::val($parsed_url, 'pass', '');
        $pass     = ($user || $pass) ? "$pass@" : '';
        $path     = \CDNVideo\Tools\Utils::val($parsed_url, 'path', '');
        $query    = \CDNVideo\Tools\Utils::val($parsed_url, 'query', '');
        $fragment = \CDNVideo\Tools\Utils::val($parsed_url, 'fragment', '');
        $query    = empty($query) ? '' : ('?' . $query);

        return "$scheme$user$pass$host$port$path$query$fragment";
    }

    /**
     * Return formated path with scheme and host
     *
     * @param string $path
     * @param string $scheme
     * @param string $host
     *
     * @return string
     */
    static public function format_path($path, \CDNVideo\Tools\Settings $settings)
    {
        $details = \CDNVideo\Tools\Utils::mb_parse_url($path);
        $details['scheme'] = empty($details['scheme']) ? $settings->getLocalScheme() : $details['scheme'];

        if (!$settings->getParseAll()) {
            // set CDN only for app
            $localHost = $settings->getLocalHost();

            if ($settings->getCDNHost() == $localHost || !empty($details['host']) && !preg_match("/$localHost/", $details['host'])) {
                return \CDNVideo\Tools\Utils::unparse_url($details);
            }
        }

        $details['host'] = $settings->getCDNHost();

        if (isset($details['path'])) {
            $details['path'] = $details['path'][0] === '/' ? $details['path'] : ('/' . $details['path']);
        }

        $query = \CDNVideo\Tools\Utils::val($details, 'query', '');
        if (!preg_match('/_cvc=/', $query)) {
            $query .= empty($query) ? '' : '&';
            $query .= '_cvc=' . $settings->getCacheInitTime();

            $details['query'] = $query;
        }

        return \CDNVideo\Tools\Utils::unparse_url($details);
    }

    /**
     * Return array key/object property from target or default value
     *
     * @param array|object $target
     * @param string       $key
     * @param string       $default
     *
     * @return string
     */
    static public function val($target, $key, $default = '')
    {
        if (is_array($target)) {
            $value = array_key_exists($key, $target) ? $target[$key] : $default;
        } elseif (is_object($target)) {
            $value = property_exists($target, $key) ? $target->{$key} : $default;
        } else {
            $value = $default;
        }

        return $value;
    }


    /**
     * Validate cache time
     *
     * @param $link
     * @param $initTime
     * @param $ttl
     *
     * @return bool
     */
    static public function update_cache_required($initTime, $ttl)
    {
        return time() > ($initTime + $ttl);
    }

    /**
     * Add cache param for URL as part of query string
     *
     * @param      $link
     * @param null $time
     *
     * @return string
     */
    static public function update_link_cache($link, $time = null)
    {
        $time    = empty($time) ? time() : $time;
        $details = \CDNVideo\Tools\Utils::mb_parse_url($link);
        $query   = \CDNVideo\Tools\Utils::val($details, 'query', '');

        $query .= empty($query) ? '' : '&';
        $query .= '_cvc=' . $time;

        $details['query'] = $query;
        $link             = \CDNVideo\Tools\Utils::unparse_url($details);

        return $link;
    }
}