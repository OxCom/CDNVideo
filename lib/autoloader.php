<?php
namespace CDNVideo;

class CDNVideoLoader
{
    public static function load($file)
    {
        $file   = str_replace('\\', DIRECTORY_SEPARATOR, $file);
        $path   = realpath(__DIR__);
        $target = $path . DIRECTORY_SEPARATOR . $file . '.php';

        if (is_file($target)) {
            include_once $target;
        } else {
            // hack (CentOS + Bitrix => namespace name in $file = \CDNVideo\Settings instead of \CDNVideo\Tools\Settings)
            // @TODO: remove this!!!
            $file = explode(DIRECTORY_SEPARATOR, $file);
            $file = implode(DIRECTORY_SEPARATOR, array($file[0], 'Tools', $file[1]));
            
            if (is_file($target)) {
                include_once $target;
            }
        }
    }

    static protected function glob_recursive($pattern, $flags = 0)
    {
        $files = glob($pattern, $flags);

        foreach (glob(dirname($pattern) . '/*', GLOB_ONLYDIR | GLOB_NOSORT) as $dir) {
            $files = array_merge($files, self::glob_recursive($dir . '/' . basename($pattern), $flags));
        }

        return $files;
    }

    public static function register()
    {
        \spl_autoload_register('CDNVideo\CDNVideoLoader::load');
    }
}

CDNVideoLoader::register();
