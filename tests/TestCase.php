<?php

class TestCase extends \PHPUnit_Framework_TestCase
{
    const TEST_DOMAIN = 'debug.lo';
    const TEST_ID = '1000';

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
                'after' => 'http://' . self::TEST_DOMAIN . '/test.js',
            ),
            array(
                'before' => '/test1.js',
                'after' => 'http://' . self::TEST_DOMAIN . '/test1.js',
            ),
            array(
                'before' => 'http://some.domain.lo/test2.js',
                'after' => 'http://' . self::TEST_DOMAIN . '/test2.js',
            ),
            array(
                'before' => '/styles/global_main.css',
                'after' => 'http://' . self::TEST_DOMAIN . '/styles/global_main.css',
            ),
            array(
                'before' => 'http://some.domain.lo/styles/main.css',
                'after' => 'http://' . self::TEST_DOMAIN . '/styles/main.css',
            ),
            array(
                'before' => '//some.domain.lo/getpro/habr/avatars/e4d/95a/d87/e4d95ad8738ec4a3b572186ea895eac0.jpg',
                'after' => 'http://' . self::TEST_DOMAIN . '/getpro/habr/avatars/e4d/95a/d87/e4d95ad8738ec4a3b572186ea895eac0.jpg',
            ),
            array(
                'before' => 'habrastorage.org/qwe.mp3',
                'after' => 'http://' . self::TEST_DOMAIN . '/habrastorage.org/qwe.mp3',
            ),
            array(
                'before' => 'video/duel.jpg',
                'after' => 'http://' . self::TEST_DOMAIN . '/video/duel.jpg',
            ),
            array(
                'before' => '/video/duel.mp4',
                'after' => 'http://' . self::TEST_DOMAIN . '/video/duel.mp4',
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
                'after' => '<a href="http://' . self::TEST_DOMAIN . '/test.js">test</a>',
            ),
            array(
                'before' => '<a href="/test1.js">test</a>',
                'after' => '<a href="http://' . self::TEST_DOMAIN . '/test1.js">test</a>',
            ),
            array(
                'before' => '<a href="http://some.domain.lo/test2.js">test</a>',
                'after' => '<a href="http://' . self::TEST_DOMAIN . '/test2.js">test</a>',
            ),
            array(
                'before' => '<a href="/styles/global_main.css">test</a>',
                'after' => '<a href="http://' . self::TEST_DOMAIN . '/styles/global_main.css">test</a>',
            ),
            array(
                'before' => '<a href="http://some.domain.lo/styles/main.css">test</a>',
                'after' => '<a href="http://' . self::TEST_DOMAIN . '/styles/main.css">test</a>',
            ),
            array(
                'before' => '<a href="//some.domain.lo/getpro/habr/avatars/e4d/95a/d87/e4d95ad8738ec4a3b572186ea895eac0.jpg">test</a>',
                'after' => '<a href="http://' . self::TEST_DOMAIN . '/getpro/habr/avatars/e4d/95a/d87/e4d95ad8738ec4a3b572186ea895eac0.jpg">test</a>',
            ),
            array(
                'before' => '<a href="habrastorage.org/qwe.mp3">test</a>',
                'after' => '<a href="http://' . self::TEST_DOMAIN . '/habrastorage.org/qwe.mp3">test</a>',
            ),
            array(
                'before' => '<a href="video/duel.jpg">test</a>',
                'after' => '<a href="http://' . self::TEST_DOMAIN . '/video/duel.jpg">test</a>',
            ),
            array(
                'before' => '<a href="/video/duel.mp4">test</a>',
                'after' => '<a href="http://' . self::TEST_DOMAIN . '/video/duel.mp4">test</a>',
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
                    <link href="http://' . self::TEST_DOMAIN . '/bitrix/cache/css/s1/books/kernel_main/kernel_main.css?141777589650918" type="text/css"  rel="stylesheet" />
                    <link href="http://' . self::TEST_DOMAIN . '/bitrix/js/main/core/css/core_canvas.css?14169893974013" type="text/css"  rel="stylesheet" />
                    <link href="http://' . self::TEST_DOMAIN . '/bitrix/cache/css/s1/books/kernel_im/kernel_im.css?1417775896141366" type="text/css"  rel="stylesheet" />
                    <link href="http://' . self::TEST_DOMAIN . '/bitrix/js/fileman/sticker.css?141681281826807" type="text/css"  rel="stylesheet" />
                    <link href="http://' . self::TEST_DOMAIN . '/bitrix/js/main/core/css/core_panel.css?141681281167432" type="text/css"  rel="stylesheet" />
                    <link href="http://' . self::TEST_DOMAIN . '/bitrix/cache/css/s1/books/page_e12c199f5355d7ff6fefbda2f3cebd76/page_e12c199f5355d7ff6fefbda2f3cebd76.css?141777595787341" type="text/css"  rel="stylesheet" />
                    <link href="http://' . self::TEST_DOMAIN . '/bitrix/cache/css/s1/books/template_9cecf12a35be3566569afd837154036a/template_9cecf12a35be3566569afd837154036a.css?141777589625081" type="text/css"  data-template-style="true"  rel="stylesheet" />
                    <link href="http://' . self::TEST_DOMAIN . '/bitrix/panel/main/popup.css?141681281122773" type="text/css"  data-template-style="true"  rel="stylesheet" />
                    <link href="http://' . self::TEST_DOMAIN . '/bitrix/panel/main/admin-public.css?141698939879610" type="text/css"  data-template-style="true"  rel="stylesheet" />
                    <link href="http://' . self::TEST_DOMAIN . '/bitrix/themes/.default/pubstyles.css?141681281149764" type="text/css"  data-template-style="true"  rel="stylesheet" />
                ',
            ),
            array(
                'before' => '
                    asdlkfj <a href="http://' . self::TEST_DOMAIN . '/sdah/styles.css">adsf</a> 34523 <a href="http://yandex.com/dasf/asd.js">adsf</a>
                ',
                'after' => '
                    asdlkfj <a href="http://' . self::TEST_DOMAIN . '/sdah/styles.css">adsf</a> 34523 <a href="http://' . self::TEST_DOMAIN . '/dasf/asd.js">adsf</a>
                ',
            ),
            /*array(
                'before' => '
                     <div style="background-image: url(/upload/iblock/157/complex.gif)"></div>
                ',
                'after' => '
                    <div style="background-image: url(http://' . self::TEST_DOMAIN . '/upload/iblock/157/complex.gif)"></div>
                ',
            ),
            array(
                'before' => '
                    <script>BX.loadScript("/bitrix/components/bitrix/player/mediaplayer/jwplayer.js", function(){setTimeout(function()</script>
                ',
                'after' => '
                    <script>BX.loadScript("http://' . self::TEST_DOMAIN . '/bitrix/components/bitrix/player/mediaplayer/jwplayer.js", function(){setTimeout(function()</script>
                ',
            ),*/
        );

        foreach ($links as $test) {
            $link = $this->CDNVideo->process($test['before']);
            $this->assertEquals($test['after'], $link);
        }
    }
}