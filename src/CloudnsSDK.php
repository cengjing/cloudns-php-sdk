<?php
namespace ClouDNS;

define('CLOUDNS_ROOT', __DIR__ . DIRECTORY_SEPARATOR);
use ClouDNS\CloudDNS\User;
use ClouDNS\CloudDNS\Zone;
use ClouDNS\CloudDNS\Record;
use ClouDNS\CloudDNS\Config;

class CloudnsSDK
{

    public static function user()
    {
        return new User();
    }

    public static function zone()
    {
        return new Zone();
    }

    public static function record()
    {
        return new Record();
    }

    public static function init(array $customConfig = array())
    {
        $config = Config::get();
        if (empty($config)) {
            $config = include (CLOUDNS_ROOT . 'config.php');
        }
        if (! empty($customConfig)) {
            $config = array_merge($config, $customConfig);
        }
        return Config::set($config);
    }
}