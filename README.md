CDNVideo
========

CDNVideo core lib for http://www.cdnvideo.ru/

Introduction
------------

CDNVideo core lib provide a small help to manage url and switch them to CDN. This lib will be extender as new API comes
Just donwload lib and include it into your project :

```php

require_once 'lib/autoloader.php';
\CDNVideo\CDNVideoLoader::register();

```

Settings
------------
Your should define some settings for lib. They should be in your contract with CNDVideo ('domain', 'client_id')

```php

$CDNVideo = new \CDNVideo\CDNVideo(array(
    'domain' => 'example.com',
    'id'     => 1000,
));

```