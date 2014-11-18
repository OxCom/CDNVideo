<?php

class TestCase extends \PHPUnit_Framework_TestCase
{
    const TEST_DOMAIN = 'debug.lo';
    const TEST_ID     = '1000';

    protected $CDNVideo;

    public function __construct()
    {
        $this->CDNVideo = new \CDNVideo\CDNVideo(array(
            'domain' => self::TEST_DOMAIN,
            'id'     => self::TEST_ID,
        ));
    }

    public function testURL()
    {
        $links = array(
            array(
                'before' => 'test.js',
                'after'  => 'http://' . self::TEST_DOMAIN . '/test.js',
            ),
            array(
                'before' => '/test1.js',
                'after'  => 'http://' . self::TEST_DOMAIN . '/test1.js',
            ),
            array(
                'before' => 'http://some.domain.lo/test2.js',
                'after'  => 'http://' . self::TEST_DOMAIN . '/test2.js',
            ),
            array(
                'before' => '/styles/global_main.css',
                'after'  => 'http://' . self::TEST_DOMAIN . '/styles/global_main.css',
            ),
            array(
                'before' => 'http://some.domain.lo/styles/main.css',
                'after'  => 'http://' . self::TEST_DOMAIN . '/styles/main.css',
            ),
            array(
                'before' => '//some.domain.lo/getpro/habr/avatars/e4d/95a/d87/e4d95ad8738ec4a3b572186ea895eac0.jpg',
                'after'  => 'http://' . self::TEST_DOMAIN . '/getpro/habr/avatars/e4d/95a/d87/e4d95ad8738ec4a3b572186ea895eac0.jpg',
            ),
            array(
                'before' => 'habrastorage.org/qwe.mp3',
                'after'  => 'http://' . self::TEST_DOMAIN . '/habrastorage.org/qwe.mp3',
            ),
            array(
                'before' => 'video/duel.jpg',
                'after'  => 'http://' . self::TEST_DOMAIN . '/video/duel.jpg',
            ),
            array(
                'before' => '/video/duel.mp4',
                'after'  => 'http://' . self::TEST_DOMAIN . '/video/duel.mp4',
            ),
            array(
                'before' => '/files/duel.pdf',
                'after'  => '/files/duel.pdf',
            ),
            array(
                'before' => '//some.domain.lo/files/duel.pdf',
                'after'  => '//some.domain.lo/files/duel.pdf',
            ),
        );

        foreach ($links as $test) {
            $link = $this->CDNVideo->processLink($test['before']);
            $this->assertEquals($test['after'], $link);
        }
    }

    public function testHTML()
    {
        $links = array(
            array(
                'before' => '<a href="test.js">test</a>',
                'after'  => '<a href="http://' . self::TEST_DOMAIN . '/test.js">test</a>',
            ),
            array(
                'before' => '<a href="/test1.js">test</a>',
                'after'  => '<a href="http://' . self::TEST_DOMAIN . '/test1.js">test</a>',
            ),
            array(
                'before' => '<a href="http://some.domain.lo/test2.js">test</a>',
                'after'  => '<a href="http://' . self::TEST_DOMAIN . '/test2.js">test</a>',
            ),
            array(
                'before' => '<a href="/styles/global_main.css">test</a>',
                'after'  => '<a href="http://' . self::TEST_DOMAIN . '/styles/global_main.css">test</a>',
            ),
            array(
                'before' => '<a href="http://some.domain.lo/styles/main.css">test</a>',
                'after'  => '<a href="http://' . self::TEST_DOMAIN . '/styles/main.css">test</a>',
            ),
            array(
                'before' => '<a href="//some.domain.lo/getpro/habr/avatars/e4d/95a/d87/e4d95ad8738ec4a3b572186ea895eac0.jpg">test</a>',
                'after'  => '<a href="http://' . self::TEST_DOMAIN . '/getpro/habr/avatars/e4d/95a/d87/e4d95ad8738ec4a3b572186ea895eac0.jpg">test</a>',
            ),
            array(
                'before' => '<a href="habrastorage.org/qwe.mp3">test</a>',
                'after'  => '<a href="http://' . self::TEST_DOMAIN . '/habrastorage.org/qwe.mp3">test</a>',
            ),
            array(
                'before' => '<a href="video/duel.jpg">test</a>',
                'after'  => '<a href="http://' . self::TEST_DOMAIN . '/video/duel.jpg">test</a>',
            ),
            array(
                'before' => '<a href="/video/duel.mp4">test</a>',
                'after'  => '<a href="http://' . self::TEST_DOMAIN . '/video/duel.mp4">test</a>',
            ),
            array(
                'before' => '<a href="/files/duel.pdf">test</a>',
                'after'  => '<a href="/files/duel.pdf">test</a>',
            ),
            array(
                'before' => '<a href="//some.domain.lo/files/duel.pdf">test</a>',
                'after'  => '<a href="//some.domain.lo/files/duel.pdf">test</a>',
            ),
        );

        foreach ($links as $test) {
            $link = $this->CDNVideo->process($test['before']);
            $this->assertEquals($test['after'], $link);
        }
    }

    public function testAPI_URLs_File()
    {
        $_SERVER['SERVER_NAME'] = 'some.domain.lo';
        $localHost              = 'some.domain.lo';
        $urls                   = array(
            array(
                'before' => 'test.js',
                'after'  => 'http://api.cdnvideo.ru:8888/' . \CDNVideo\Tools\Api::METHOD_PURGE_FILE . '?id=' . self::TEST_ID . '&type=' . \CDNVideo\Tools\Api::CONTENT_TYPE_HTTP . '&object=' . urlencode('http://' . $localHost . '/test.js'),
            ),
            array(
                'before' => '/test1.js',
                'after'  => 'http://api.cdnvideo.ru:8888/' . \CDNVideo\Tools\Api::METHOD_PURGE_FILE . '?id=' . self::TEST_ID . '&type=' . \CDNVideo\Tools\Api::CONTENT_TYPE_HTTP . '&object=' . urlencode('http://' . $localHost . '/test1.js'),
            ),
            array(
                'before' => 'http://some.domain.lo/test2.js',
                'after'  => 'http://api.cdnvideo.ru:8888/' . \CDNVideo\Tools\Api::METHOD_PURGE_FILE . '?id=' . self::TEST_ID . '&type=' . \CDNVideo\Tools\Api::CONTENT_TYPE_HTTP . '&object=' . urlencode('http://some.domain.lo/test2.js'),
            ),
        );

        $settings = new \CDNVideo\Tools\Settings(array(
            'domain' => self::TEST_DOMAIN,
            'id'     => self::TEST_ID,
        ));
        $api      = new \CDNVideo\Tools\Api($settings);

        foreach ($urls as $test) {
            $test['before'] = \CDNVideo\Tools\Utils::format_path($test['before'], $settings->getLocalScheme(), $settings->getLocalHost());
            $link           = $api->buildURL(\CDNVideo\Tools\Api::METHOD_PURGE_FILE, array('type' => \CDNVideo\Tools\Api::CONTENT_TYPE_HTTP, 'object' => $test['before']));
            $this->assertEquals($test['after'], $link);
        }
    }

    public function testAPI_URLs_All()
    {
        $_SERVER['SERVER_NAME'] = 'some.domain.lo';
        $urls                   = array(
            array(
                'before' => '*',
                'after'  => 'http://api.cdnvideo.ru:8888/' . \CDNVideo\Tools\Api::METHOD_PURGE_ALL . '?id=' . self::TEST_ID . '&type=' . \CDNVideo\Tools\Api::CONTENT_TYPE_HTTP . '&object=' . urlencode('*'),
            ),
            array(
                'before' => '*',
                'after'  => 'http://api.cdnvideo.ru:8888/' . \CDNVideo\Tools\Api::METHOD_PURGE_ALL . '?id=' . self::TEST_ID . '&type=' . \CDNVideo\Tools\Api::CONTENT_TYPE_HTTP . '&object=' . urlencode('*'),
            ),
            array(
                'before' => '*',
                'after'  => 'http://api.cdnvideo.ru:8888/' . \CDNVideo\Tools\Api::METHOD_PURGE_ALL . '?id=' . self::TEST_ID . '&type=' . \CDNVideo\Tools\Api::CONTENT_TYPE_HTTP . '&object=' . urlencode('*'),
            ),
        );

        $settings = new \CDNVideo\Tools\Settings(array(
            'domain' => self::TEST_DOMAIN,
            'id'     => self::TEST_ID,
        ));
        $api      = new \CDNVideo\Tools\Api($settings);

        foreach ($urls as $test) {
            $link = $api->buildURL(\CDNVideo\Tools\Api::METHOD_PURGE_ALL, array('type' => \CDNVideo\Tools\Api::CONTENT_TYPE_HTTP, 'object' => $test['before']));
            $this->assertEquals($test['after'], $link);
        }
    }
}