<?php

class TestCase extends \PHPUnit_Framework_TestCase
{
    const TEST_DOMAIN      = 'debug.lo';
    const TEST_SERVER_NAME = 'example.com';
    const TEST_ID          = '1000';
    const TEST_TTL         = 3600;

    protected $CDNVideo;
    protected $initTime;

    public function __construct()
    {
        $this->initTime = time();
        $this->CDNVideo = new \CDNVideo\CDNVideo(array(
            'domain'    => self::TEST_DOMAIN,
            'id'        => self::TEST_ID,
            'init_time' => $this->initTime,
            'ttl'       => self::TEST_TTL,
            'parse_all' => true,
        ));
    }

    public function testURL()
    {
        $links = array(
            array(
                'before' => 'test.js',
                'after' => 'http://' . self::TEST_DOMAIN . '/test.js?_cvc=' . $this->initTime,
            ),
            array(
                'before' => '/test1.js',
                'after' => 'http://' . self::TEST_DOMAIN . '/test1.js?_cvc=' . $this->initTime,
            ),
            array(
                'before' => 'http://some.domain.lo/test2.js',
                'after' => 'http://' . self::TEST_DOMAIN . '/test2.js?_cvc=' . $this->initTime,
            ),
            array(
                'before' => '/styles/global_main.css',
                'after' => 'http://' . self::TEST_DOMAIN . '/styles/global_main.css?_cvc=' . $this->initTime,
            ),
            array(
                'before' => 'http://some.domain.lo/styles/main.css',
                'after' => 'http://' . self::TEST_DOMAIN . '/styles/main.css?_cvc=' . $this->initTime,
            ),
            array(
                'before' => '//some.domain.lo/getpro/habr/avatars/e4d/95a/d87/e4d95ad8738ec4a3b572186ea895eac0.jpg',
                'after' => 'http://' . self::TEST_DOMAIN . '/getpro/habr/avatars/e4d/95a/d87/e4d95ad8738ec4a3b572186ea895eac0.jpg?_cvc=' . $this->initTime,
            ),
            array(
                'before' => 'habrastorage.org/qwe.mp3',
                'after' => 'http://' . self::TEST_DOMAIN . '/habrastorage.org/qwe.mp3?_cvc=' . $this->initTime,
            ),
            array(
                'before' => 'video/duel.jpg',
                'after' => 'http://' . self::TEST_DOMAIN . '/video/duel.jpg?_cvc=' . $this->initTime,
            ),
            array(
                'before' => '/video/duel.mp4',
                'after' => 'http://' . self::TEST_DOMAIN . '/video/duel.mp4?_cvc=' . $this->initTime,
            ),
            array(
                'before' => '/files/duel.pdf',
                'after' => '/files/duel.pdf',
            ),
            array(
                'before' => '//some.domain.lo/files/duel.pdf',
                'after' => '//some.domain.lo/files/duel.pdf',
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
                'after' => '<a href="http://' . self::TEST_DOMAIN . '/test.js?_cvc=' . $this->initTime .'">test</a>',
            ),
            array(
                'before' => '<a href="/test1.js">test</a>',
                'after' => '<a href="http://' . self::TEST_DOMAIN . '/test1.js?_cvc=' . $this->initTime .'">test</a>',
            ),
            array(
                'before' => '<a href="http://some.domain.lo/test2.js">test</a>',
                'after' => '<a href="http://' . self::TEST_DOMAIN . '/test2.js?_cvc=' . $this->initTime .'">test</a>',
            ),
            array(
                'before' => '<a href="/styles/global_main.css">test</a>',
                'after' => '<a href="http://' . self::TEST_DOMAIN . '/styles/global_main.css?_cvc=' . $this->initTime .'">test</a>',
            ),
            array(
                'before' => '<a href="http://some.domain.lo/styles/main.css">test</a>',
                'after' => '<a href="http://' . self::TEST_DOMAIN . '/styles/main.css?_cvc=' . $this->initTime .'">test</a>',
            ),
            array(
                'before' => '<a href="//some.domain.lo/getpro/habr/avatars/e4d/95a/d87/e4d95ad8738ec4a3b572186ea895eac0.jpg">test</a>',
                'after' => '<a href="http://' . self::TEST_DOMAIN . '/getpro/habr/avatars/e4d/95a/d87/e4d95ad8738ec4a3b572186ea895eac0.jpg?_cvc=' . $this->initTime .'">test</a>',
            ),
            array(
                'before' => '<a href="habrastorage.org/qwe.mp3">test</a>',
                'after' => '<a href="http://' . self::TEST_DOMAIN . '/habrastorage.org/qwe.mp3?_cvc=' . $this->initTime .'">test</a>',
            ),
            array(
                'before' => '<a href="video/duel.jpg">test</a>',
                'after' => '<a href="http://' . self::TEST_DOMAIN . '/video/duel.jpg?_cvc=' . $this->initTime .'">test</a>',
            ),
            array(
                'before' => '<a href="/video/duel.mp4">test</a>',
                'after' => '<a href="http://' . self::TEST_DOMAIN . '/video/duel.mp4?_cvc=' . $this->initTime .'">test</a>',
            ),
            array(
                'before' => '<a href="/files/duel.pdf">test</a>',
                'after' => '<a href="/files/duel.pdf">test</a>',
            ),
            array(
                'before' => '<a href="//some.domain.lo/files/duel.pdf">test</a>',
                'after' => '<a href="//some.domain.lo/files/duel.pdf">test</a>',
            ),
            array(
                'before' => '
                    <link href="/bitrix/cache/css/s1/books/kernel_main/kernel_main.css?141777589650918" type="text/css"  rel="stylesheet" />
                    <link href="/bitrix/js/main/core/css/core_canvas.css?14169893974013" type="text/css"  rel="stylesheet" />
                    <link href="/bitrix/cache/css/s1/books/kernel_im/kernel_im.css?1417775896141366" type="text/css"  rel="stylesheet" />
                    <link href="/bitrix/js/fileman/sticker.css?141681281826807" type="text/css"  rel="stylesheet" />
                    <link href="/bitrix/js/main/core/css/core_panel.css?141681281167432" type="text/css"  rel="stylesheet" />
                    <link href="/bitrix/cache/css/s1/books/page_e12c199f5355d7ff6fefbda2f3cebd76/page_e12c199f5355d7ff6fefbda2f3cebd76.css?141777595787341" type="text/css"  rel="stylesheet" />
                    <link href="/bitrix/cache/css/s1/books/template_9cecf12a35be3566569afd837154036a/template_9cecf12a35be3566569afd837154036a.css?141777589625081" type="text/css"  data-template-style="true"  rel="stylesheet" />
                    <link href="/bitrix/panel/main/popup.css?141681281122773" type="text/css"  data-template-style="true"  rel="stylesheet" />
                    <link href="/bitrix/panel/main/admin-public.css?141698939879610" type="text/css"  data-template-style="true"  rel="stylesheet" />
                    <link href="/bitrix/themes/.default/pubstyles.css?141681281149764" type="text/css"  data-template-style="true"  rel="stylesheet" />
                ',
                'after' => '
                    <link href="http://' . self::TEST_DOMAIN . '/bitrix/cache/css/s1/books/kernel_main/kernel_main.css?141777589650918&_cvc=' . $this->initTime .'" type="text/css"  rel="stylesheet" />
                    <link href="http://' . self::TEST_DOMAIN . '/bitrix/js/main/core/css/core_canvas.css?14169893974013&_cvc=' . $this->initTime .'" type="text/css"  rel="stylesheet" />
                    <link href="http://' . self::TEST_DOMAIN . '/bitrix/cache/css/s1/books/kernel_im/kernel_im.css?1417775896141366&_cvc=' . $this->initTime .'" type="text/css"  rel="stylesheet" />
                    <link href="http://' . self::TEST_DOMAIN . '/bitrix/js/fileman/sticker.css?141681281826807&_cvc=' . $this->initTime .'" type="text/css"  rel="stylesheet" />
                    <link href="http://' . self::TEST_DOMAIN . '/bitrix/js/main/core/css/core_panel.css?141681281167432&_cvc=' . $this->initTime .'" type="text/css"  rel="stylesheet" />
                    <link href="http://' . self::TEST_DOMAIN . '/bitrix/cache/css/s1/books/page_e12c199f5355d7ff6fefbda2f3cebd76/page_e12c199f5355d7ff6fefbda2f3cebd76.css?141777595787341&_cvc=' . $this->initTime .'" type="text/css"  rel="stylesheet" />
                    <link href="http://' . self::TEST_DOMAIN . '/bitrix/cache/css/s1/books/template_9cecf12a35be3566569afd837154036a/template_9cecf12a35be3566569afd837154036a.css?141777589625081&_cvc=' . $this->initTime .'" type="text/css"  data-template-style="true"  rel="stylesheet" />
                    <link href="http://' . self::TEST_DOMAIN . '/bitrix/panel/main/popup.css?141681281122773&_cvc=' . $this->initTime .'" type="text/css"  data-template-style="true"  rel="stylesheet" />
                    <link href="http://' . self::TEST_DOMAIN . '/bitrix/panel/main/admin-public.css?141698939879610&_cvc=' . $this->initTime .'" type="text/css"  data-template-style="true"  rel="stylesheet" />
                    <link href="http://' . self::TEST_DOMAIN . '/bitrix/themes/.default/pubstyles.css?141681281149764&_cvc=' . $this->initTime .'" type="text/css"  data-template-style="true"  rel="stylesheet" />
                ',
            ),
            array(
                'before' => '
                    asdlkfj <a href="http://' . self::TEST_DOMAIN . '/sdah/styles.css">adsf</a> 34523 <a href="http://yandex.com/dasf/asd.js">adsf</a>
                ',
                'after' => '
                    asdlkfj <a href="http://' . self::TEST_DOMAIN . '/sdah/styles.css?_cvc=' . $this->initTime .'">adsf</a> 34523 <a href="http://' . self::TEST_DOMAIN . '/dasf/asd.js?_cvc=' . $this->initTime .'">adsf</a>
                ',
            ),
            array(
                'before' => '
                    <div style="background-image: url(/upload/iblock/157/complex.gif)"></div>
                ',
                'after' => '
                    <div style="background-image: url(http://' . self::TEST_DOMAIN . '/upload/iblock/157/complex.gif?_cvc=' . $this->initTime .')"></div>
                ',
            ),
            array(
                'before' => '
                    complex <a href="http://example.com/sdah/styles.css">complex</a> complex <a href="http://yandex.com/dasf/asd.js">complex</a>
                    <a href=\'http://example.com/sdah/styles.css\'>complex</a> complex <a href=\'http://example.com/sdah/styles.css?complex\'>complex</a> 34523
                    <link href="/bitrix/cache/css/s1/books/kernel_main/kernel_main.css?141777589650918" type="text/css"  rel="stylesheet" />
                    <div style="background-image: url(http://google.com/upload/iblock/157/complex.gif)"></div>
                    <div style="background-image: url(http://google.com/upload/iblock/157/complex.gif?asd=qwe)"></div>
                ',
                'after' => '
                    complex <a href="http://' . self::TEST_DOMAIN . '/sdah/styles.css?_cvc=' . $this->initTime .'">complex</a> complex <a href="http://' . self::TEST_DOMAIN . '/dasf/asd.js?_cvc=' . $this->initTime .'">complex</a>
                    <a href=\'http://' . self::TEST_DOMAIN . '/sdah/styles.css?_cvc=' . $this->initTime .'\'>complex</a> complex <a href=\'http://' . self::TEST_DOMAIN . '/sdah/styles.css?complex&_cvc=' . $this->initTime .'\'>complex</a> 34523
                    <link href="http://' . self::TEST_DOMAIN . '/bitrix/cache/css/s1/books/kernel_main/kernel_main.css?141777589650918&_cvc=' . $this->initTime .'" type="text/css"  rel="stylesheet" />
                    <div style="background-image: url(http://' . self::TEST_DOMAIN . '/upload/iblock/157/complex.gif?_cvc=' . $this->initTime .')"></div>
                    <div style="background-image: url(http://' . self::TEST_DOMAIN . '/upload/iblock/157/complex.gif?asd=qwe&_cvc=' . $this->initTime .')"></div>
                ',
            ),
        );

        foreach ($links as $test) {
            $link = $this->CDNVideo->process($test['before']);
            $this->assertEquals($test['after'], $link);
        }
    }

    public function testCache()
    {
        $time     = time();
        $ttl      = 1;
        $CDNVideo = new \CDNVideo\CDNVideo(array(
            'domain'    => self::TEST_DOMAIN,
            'id'        => self::TEST_ID,
            'init_time' => $time,
            'ttl'       => $ttl,
            'parse_all' => true,
        ));

        $links = array(
            array(
                'before' => '<a href="test.js">test</a>',
                'after' => '<a href="http://' . self::TEST_DOMAIN . '/test.js?_cvc=' . $time .'">test</a>',
            ),
            array(
                'before' => '<a href="test.js?qwe=123">test</a>',
                'after' => '<a href="http://' . self::TEST_DOMAIN . '/test.js?qwe=123&_cvc=' . $time .'">test</a>',
            ),
        );

        foreach ($links as $test) {
            $link = $CDNVideo->process($test['before']);
            $this->assertEquals($test['after'], $link);
        }

        sleep($ttl * 5);

        $link     = $CDNVideo->process('<a href="test.js">test</a>');
        $nextTime = $CDNVideo->getCacheTime();
        $this->assertNotEquals('<a href="http://' . self::TEST_DOMAIN . '/test.js?_cvc=' . $time . '">test</a>', $link);
        $this->assertEquals('<a href="http://' . self::TEST_DOMAIN . '/test.js?_cvc=' . $nextTime . '">test</a>', $link);
    }

    public function testDomainParse()
    {
        $CDNVideo = new \CDNVideo\CDNVideo(array(
            'domain'      => self::TEST_DOMAIN,
            'id'          => self::TEST_ID,
            'init_time'   => $this->initTime,
            'ttl'         => self::TEST_TTL,
            'server_name' => self::TEST_SERVER_NAME,
        ));

        $links = array(
            array(
                'before' => '<a href="test.js">test</a>',
                'after'  => '<a href="http://' . self::TEST_DOMAIN . '/test.js?_cvc=' . $this->initTime .'">test</a>',
            ),
            array(
                'before' => '<a href="/test1.js">test</a>',
                'after'  => '<a href="http://' . self::TEST_DOMAIN . '/test1.js?_cvc=' . $this->initTime .'">test</a>',
            ),
            array(
                'before' => '<a href="http://' . self::TEST_SERVER_NAME . '/test.js">test</a>',
                'after'  => '<a href="http://' . self::TEST_DOMAIN . '/test.js?_cvc=' . $this->initTime .'">test</a>',
            ),
            array(
                'before' => '<a href="http://' . self::TEST_DOMAIN . '/test.js">test</a>',
                'after'  => '<a href="http://' . self::TEST_DOMAIN . '/test.js">test</a>',
            ),
            array(
                'before' => '<a href="http://google.com/test1.js">test</a>',
                'after'  => '<a href="http://google.com/test1.js">test</a>',
            ),
            array(
                'before' => '<a href="//google.com/test1.js">test</a>',
                'after'  => '<a href="http://google.com/test1.js">test</a>',
            ),
        );

        foreach ($links as $test) {
            $link = $CDNVideo->process($test['before']);
            $this->assertEquals($test['after'], $link);
        }
    }

    public function testMultiDomain()
    {
        $domain1  = 'test1.com';
        $domain2  = 'test2.com';
        $domain3  = 'test3.com';
        $CDNVideo = new \CDNVideo\CDNVideo(array(
            'domain'      => array($domain1, $domain2, $domain3),
            'id'          => self::TEST_ID,
            'init_time'   => $this->initTime,
            'ttl'         => self::TEST_TTL,
            'server_name' => self::TEST_SERVER_NAME,
        ));

        $links = array(
            '<a href="test.js">test</a>',
            '<a href="/test1.js">test</a>',
            '<a href="http://' . self::TEST_SERVER_NAME . '/test1.js">test</a>',
        );

        foreach ($links as $test) {
            $result = $CDNVideo->process($test);
            $this->assertTrue(!!preg_match("/($domain1)|($domain2)|($domain3)/", $result));
        }
    }

    public function testChain()
    {
        $domain1       = 'test1.com';
        $domain2       = 'test2.com';
        $domain1Targets = array(
            'css', 'js', 'jpeg', 'jpg', 'png', 'gif'
        );
        $domain2Targets = array(
            'mp3', 'mp4', 'ogg', 'flv'
        );

        $chainElement1 = array(
            'domain'      => $domain1,
            'id'          => self::TEST_ID,
            'init_time'   => $this->initTime,
            'ttl'         => self::TEST_TTL,
            'server_name' => self::TEST_SERVER_NAME,
            'targets'     => $domain1Targets,
        );
        $chainElement2 = array(
            'domain'      => $domain2,
            'id'          => self::TEST_ID,
            'init_time'   => $this->initTime,
            'ttl'         => self::TEST_TTL,
            'server_name' => self::TEST_SERVER_NAME,
            'targets'     => $domain2Targets,
        );

        $CDNVideoChain = new \CDNVideo\CDNChain();
        $CDNVideoChain
            ->addChainElement($chainElement1)
            ->addChainElement($chainElement2);

        $links = array(
            'js'   => '<a href="/test.js">test</a>',
            'css'  => '<a href="/test.css">test</a>',
            'jpeg' => '<a href="/test.jpeg">test</a>',
            'png'  => '<a href="/test.png">test</a>',
            'mp3'  => '<a href="/test.mp3">test</a>',
            'mp4'  => '<a href="/test.mp4">test</a>',
            'ogg'  => '<a href="/test.ogg">test</a>',
        );

        foreach ($links as $key => $test) {
            $result = $CDNVideoChain->process($test);
            if (in_array($key, $domain1Targets)) {
                $this->assertTrue(!!preg_match("/($domain1)/", $result));
            } else if (in_array($key, $domain2Targets)) {
                $this->assertTrue(!!preg_match("/($domain2)/", $result));
            } else {
                $this->assertTrue(false);
            }
        }

        /**
         * Test cache time functions
         */
        $chainCacheTime = $CDNVideoChain->getCacheTime();
        $this->assertEquals(2, count($chainCacheTime));

        $chainElement1['chainKey'] = 'kay_1';
        $chainElement2['chainKey'] = 'kay_2';

        $CDNVideoChain = new \CDNVideo\CDNChain();
        $CDNVideoChain
            ->addChainElement($chainElement1)
            ->addChainElement($chainElement2);

        $time = $CDNVideoChain->getCacheTime($chainElement1['chainKey']);
        $this->assertEquals($this->initTime, $time);

        $time = $CDNVideoChain->getCacheTime($chainElement2['chainKey']);
        $this->assertEquals($this->initTime, $time);
    }

//    public function testMultiReplace()
//    {
//        $htmlBefore = '
//            complex <a href="/sdah/styles.css">complex</a> complex <a href="/sdah/styles.css">complex</a>
//            complex <a href="/sdah/styles.css">complex</a>
//        ';
//        $htmlAfter = '
//            complex <a href="http://' . self::TEST_DOMAIN . '/sdah/styles.css?_cvc=' . $this->initTime .'">complex</a> complex <a href="http://' . self::TEST_DOMAIN . '/sdah/styles.css?_cvc=' . $this->initTime .'">complex</a>
//            complex <a href="http://' . self::TEST_DOMAIN . '/sdah/styles.css?_cvc=' . $this->initTime .'">complex</a>
//        ';
//
//        $link = $this->CDNVideo->process($htmlBefore);
//        $this->assertEquals($htmlAfter, $link);
//    }
}