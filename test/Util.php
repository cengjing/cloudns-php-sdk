<?php
class Util
{

    /**
     * 生成uuid 为随机唯一32位md5值
     *
     * @return string uuid
     */
    static public function create_uuid()
    {
        return md5(uniqid(mt_rand(), true) . mt_rand() . mt_rand() . mt_rand());
    }
}