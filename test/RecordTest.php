<?php
require __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'autoload.php';
require __DIR__ . DIRECTORY_SEPARATOR . 'Util.php';
use ClouDNS\CloudnsSDK;
class RecordTest extends \PHPUnit_Framework_TestCase
{
    private $logic = null;
    private $testZoneRoot = 'zone.com';

    function __construct()
    {
        CloudnsSDK::init();
        $this->logic = CloudnsSDK::record();
    }

    public function test_rec_load_all()
    {
        /* verify default params */
        $ret = $this->logic->rec_load_all($this->testZoneRoot);
        $this->assertTrue(is_array($ret) && is_array($ret['data']));
        /* verify specify params */
        $offset = 1;
        $number = 5;
        $ret = $this->logic->rec_load_all($this->testZoneRoot, $offset, $number);
        $this->assertTrue(is_array($ret) && is_array($ret['data']));
        $this->assertEquals($ret['offset'], $offset);
        $this->assertTrue($ret['number'] <= $number);
        return $ret['data'];
    }

    public function test_rec_load_size()
    {
        $ret = $this->logic->rec_load_size($this->testZoneRoot);
        $this->assertTrue(is_numeric($ret));
        return $ret;
    }

    public function test_rec_load()
    {
        /* test not right rid */
        $rid = '-1';
        $ret = $this->logic->rec_load($this->testZoneRoot, $rid);
        $this->assertTrue(is_null($ret));
        /* test correct rid */
        $rid = array_pop($this->getRecords());
        $rid = $rid['id'];
        $ret = $this->logic->rec_load($this->testZoneRoot, $rid);
        $this->assertTrue(is_array($ret) && $rid == $ret['id']);
    }

    public function test_rec_new($name = '', $content = '')
    {
        /* test add status */
        $type = 'A';
        $name = empty($name) ? 'sub' : $name;
        $content = empty($content) ? $this->createTestIP() : $content;
        $isp = 'tel';
        $ttl = '300';
        $z = $this->testZoneRoot;
        $ret = $this->logic->rec_new($z, $type, $name, $content, $isp, $ttl);
        $this->assertTrue(is_array($ret) && $ret['status'] == 0 && $ret['zname'] == $z);
        /* test add the same record */
        try {
            $ret = $this->logic->rec_new($z, $type, $name, $content, $isp, $ttl);
            $this->assertTrue(false);
        } catch (Exception $e) {
            $ret = preg_match('#has existed#i', $e->getMessage());
            $this->assertTrue($ret && true);
        }
        /* test add not exist zone */
        $z = $this->createSubZone();
        $content = $this->createTestIP();
        try {
            $ret = $this->logic->rec_new($z, $type, $name, $content, $isp, $ttl);
            $this->assertTrue(false);
        } catch (Exception $e) {
            $ret = preg_match('#zone not exists#i', $e->getMessage());
            $this->assertTrue($ret && true);
        }
    }

    public function test_rec_edit()
    {
        /* test an exist record */
        $r = array_pop($this->getRecords());
        $ip = $this->createTestIP();
        $ret = $this->logic->rec_edit($this->testZoneRoot, $r['id'], $r['type'], $r['name'], $ip, $r['isp'], $r['ttl']);
        /* content not modified */
        try {
            $ret = $this->logic->rec_edit($this->testZoneRoot, $r['id'], $r['type'], $r['name'], $ip, $r['isp'], $r['ttl']);
            $this->assertTrue(false);
        } catch (Exception $e) {
            /* error msg : record not modified */
            $ret = preg_match('#not modified#i', $e->getMessage());
            $this->assertTrue($ret && true);
        }
    }

    public function test_rec_delete()
    {
        $r = array_shift($this->getRecords());
        $ret = $this->logic->rec_delete($this->testZoneRoot, $r['id']);
        $this->assertEquals($ret['id'], $r['id']);
        /* read to test if the record has exist */
        $ret = $this->logic->rec_load($this->testZoneRoot, $r['id']);
        $this->assertTrue(is_null($ret));
    }

    public function test_bulk_rec_delete()
    {
        $testNum = 3;
        /* add three records */
        for ($i = 0; $i < $testNum; $i ++) {
            $this->test_rec_new();
        }
        /* sleep 5 seconds to wait the record status to be 1 */
        sleep(5);
        $rids = array();
        $rs = $this->getRecords();
        for ($i = 0; $i < $testNum; $i ++) {
            $r = array_pop($rs);
            if ($r['id'] === 0 || ! empty($r['id'])) {
                array_push($rids, $r['id']);
            }
        }
        sort($rids);
        $ret = $this->logic->bulk_rec_delete($this->testZoneRoot, $rids);
        $deleteRids = array();
        foreach ($ret as $r) {
            array_push($deleteRids, $r['id']);
        }
        sort($deleteRids);
        $this->assertEquals($rids, $deleteRids);
        /* read to test if the record has exist */
        foreach ($rids as $id) {
            $ret = $this->logic->rec_load($this->testZoneRoot, $id);
            $this->assertTrue(is_null($ret));
        }
    }

    public function test_rec_load_by_name($name = '')
    {
        $testNum = 3;
        $name = empty($name) ? $this->createSubZone() : $name;
        for ($i = 0; $i < $testNum; $i ++) {
            $this->test_rec_new($name);
        }
        $ret = $this->logic->rec_load_by_name($this->testZoneRoot, $name);
        $this->assertEquals($testNum, $ret['number']);
        foreach ($ret['data'] as $r) {
            $this->assertEquals($r['name'], $name);
        }
        return $ret['data'];
    }

    public function test_rec_delete_by_name()
    {
        $name = $this->createSubZone();
        $this->test_rec_load_by_name($name);
        /* sleep 5 seconds to wait the record status to be 1 */
        sleep(5);
        $ret = $this->logic->rec_delete_by_name($this->testZoneRoot, $name);
        $load = $this->logic->rec_load_by_name($this->testZoneRoot, $name);
        $this->assertEquals(0, $load['number']);
    }

    public function test_bulk_rec_new($num = 5, $name = '')
    {
        $name = empty($name) ? $this->createSubZone() : $name;
        $records = array();
        for ($i = 0; $i < $num; $i ++) {
            array_push($records, array(
                'type' => 'A',
                'name' => $name,
                'ttl' => '300',
                'isp' => 'tel',
                'content' => $this->createTestIP()
            ));
        }
        $ret = $this->logic->bulk_rec_new($this->testZoneRoot, $records);
        $this->assertEquals(count($ret), $num);
    }

    public function test_rec_load_by_prefix()
    {
        $num = 6;
        $prefix = substr(Util::create_uuid(), 0, 10);
        for ($i = 0; $i < $num; $i ++) {
            $name = Util::create_uuid() . '.' . $prefix;
            $this->test_rec_new($name);
        }
        $name = '*.' . $prefix;
        $ret = $this->logic->rec_load_by_prefix($this->testZoneRoot, $name);
        $this->assertEquals($ret['number'], $num);
    }

    private function getRecords()
    {
        $ret = $this->test_rec_load_all();
        if (empty($ret)) {
            $this->test_rec_new();
            $ret = $this->test_rec_load_all();
        }
        return $ret;
    }

    private function createTestIP()
    {
        return rand(0, 255) . '.' . rand(0, 255) . '.' . rand(0, 255) . '.' . rand(0, 255);
    }

    private function createSubZone()
    {
        return Util::create_uuid() . '.' . $this->testZoneRoot;
    }
}