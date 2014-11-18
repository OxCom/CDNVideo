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

    /**
     * Return formated path with scheme and host
     *
     * @param string $path
     * @param string $scheme
     * @param string $host
     *
     * @return string
     */
    static public function format_path($path, $scheme, $host)
    {
        $details = \CDNVideo\Tools\Utils::mb_parse_url($path);
        $details['scheme'] = empty($details['scheme']) ? $scheme : $details['scheme'];
        $details['host']   = $host;

        if (isset($details['path'])) {
            $details['path'] = $details['path'][0] === '/' ? $details['path'] : ('/' . $details['path']);
        }

        return \CDNVideo\Tools\Utils::unparse_url($details);
    }
}