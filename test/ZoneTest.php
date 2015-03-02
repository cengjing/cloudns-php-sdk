<?php
require __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'autoload.php';
require __DIR__ . DIRECTORY_SEPARATOR . 'Util.php';
use ClouDNS\CloudnsSDK;
class ZoneTest extends \PHPUnit_Framework_TestCase
{
    private $logic = null;
    private $testZoneRoot = 'zone.com';

    function __construct()
    {
        CloudnsSDK::init();
        $this->logic = CloudnsSDK::zone();
    }

    public function test_zone_new()
    {
        $new_zone = $this->createSubZone();
        $ret = $this->logic->zone_new($new_zone);
        $ret = preg_match('#has been submitted#i', $ret);
        $this->assertTrue($ret && true);
    }

    public function test_zone_load_multi()
    {
        /* verify default params */
        $ret = $this->logic->zone_load_multi();
        $this->assertTrue(is_array($ret) && is_array($ret['data']));
        /* verify specify params */
        $offset = 1;
        $number = 5;
        $ret = $this->logic->zone_load_multi($offset, $number);
        $this->assertTrue(is_array($ret) && is_array($ret['data']));
        $this->assertEquals($ret['offset'], $offset);
        $this->assertTrue($ret['number'] <= $number);
    }

    /**
     * @TODO zone_check 有bug
     */
    public function _test_zone_check()
    {
        /* check root zone */
        $zones = array(
            $this->testZoneRoot
        );
        $ret = $this->logic->zone_check($zones);
        $this->assertTrue(is_array($ret) && is_array($ret['data']));
        /* check multi zones*/
        $zones = array(
            $this->testZoneRoot,
            $this->createSubZone(),
            $this->createSubZone()
        );
        $ret = $this->logic->zone_check($zones);
        $this->assertTrue(is_array($ret) && is_array($ret['data']));
    }

    public function _test_zone_delete()
    {
        $ret = $this->logic->zone_delete();
    }

    private function createSubZone()
    {
        return Util::create_uuid() . '.' . $this->testZoneRoot;
    }
}