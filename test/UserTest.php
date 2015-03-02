<?php
require __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'autoload.php';
use ClouDNS\CloudnsSDK;
use ClouDNS\CloudDNS\Config;
class UserTest extends \PHPUnit_Framework_TestCase
{
    private $logic = null;

    function __construct()
    {
        CloudnsSDK::init();
        $this->logic = CloudnsSDK::user();
    }

    public function test_user_edit_token()
    {
        $newTkn = $this->logic->user_edit_token();
        /* verify create tkn status */
        $this->assertTrue(! empty($newTkn));
        $auth = Config::get('auth');
        /* verify write config status */
        $this->assertEquals($auth['tkn'], $newTkn);
    }

    public function test_userlog_load_all()
    {
        /* verify default params */
        $ret = $this->logic->userlog_load_all();
        $this->assertTrue(is_array($ret) && is_array($ret['data']));
        $this->assertEquals($ret['offset'], 0);
        $this->assertTrue($ret['number'] <= 100);
        /* verify specify params */
        $offset = 1;
        $number = 5;
        $ret = $this->logic->userlog_load_all($offset, $number);
        $this->assertTrue(is_array($ret) && is_array($ret['data']));
        $this->assertEquals($ret['offset'], $offset);
        $this->assertTrue($ret['number'] <= $number);
    }

    public function test_applyhist_load_all()
    {
        /* verify default params */
        $ret = $this->logic->applyhist_load_all();
        $this->assertTrue(is_array($ret) && is_array($ret['data']));
        $this->assertEquals($ret['offset'], 0);
        $this->assertTrue($ret['number'] <= 100);
        /* verify specify params */
        $offset = 1;
        $number = 5;
        $ret = $this->logic->applyhist_load_all($offset, $number);
        $this->assertTrue(is_array($ret) && is_array($ret['data']));
        $this->assertEquals($ret['offset'], $offset);
        $this->assertTrue($ret['number'] <= $number);
    }
}