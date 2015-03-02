<?php
namespace ClouDNS\CloudDNS;

use Curl\Curl;

class Service
{
    private $curlObj = null;

    public function call($action, array $param)
    {
        $param['a'] = $action;
        /* add auth info into query params */
        $param = array_merge($this->auth(), $param);
        $ret = $this->curl()->post($this->api(), $param);
        $ret = json_decode($ret, true);
        if (! $ret) {
            throw new ClouDNSException('curl return error', 10400);
        }
        if ($ret['errno'] !== 0 && $ret['errno'] !== '0') {
            throw new ClouDNSException($ret['errmsg'], $ret['errno']);
        }
        return $ret['result'] ;
    }

    private function api()
    {
        return Config::get('api');
    }

    private function auth()
    {
        $auth = Config::get('auth');
        if (empty($auth) || ! is_array($auth)) {
            throw new ClouDNSException('auth config type error', 10400);
        }
        return $auth;
    }

    private function curl()
    {
        if (empty($this->curlObj)) {
            $curl = new Curl();
            /* close ssl verify */
            $curl->setOpt(CURLOPT_SSL_VERIFYPEER, false);
            $this->curlObj = $curl;
        }
        return $this->curlObj;
    }
}