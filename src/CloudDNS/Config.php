<?php
namespace ClouDNS\CloudDNS;

class Config
{
    private static $config = array();

    public static function set($name, array $value = array())
    {
        $config = &self::$config;
        if (func_num_args() == 2) {
            $config[$name] = $value;
        } elseif (is_array($name)) {
            $config = array_merge($config, $name);
        } else {
            throw new ClouDNSException('set config params error');
        }
        return true;
    }

    public static function get($name = '')
    {
        if (! empty($name)) {
            return self::$config[$name];
        }
        return self::$config;
    }

    public static function changeTkn($tkn)
    {
        $auth = self::get('auth');
        $oldTkn = $auth['tkn'];
        $configPath = CLOUDNS_ROOT . 'config.php';
        $configCont = file_get_contents($configPath);
        if (preg_match("#{$oldTkn}#", $configCont)) {
            $ret = file_put_contents($configPath, str_replace($oldTkn, $tkn, $configCont));
            if (! $ret) {
                throw new ClouDNSException('write new tkn into config file fail,check the config.php file\'s wirtten permission');
            }
        }
        $auth['tkn'] = $tkn;
        self::set(array(
            'auth' => $auth
        ));
    }
}