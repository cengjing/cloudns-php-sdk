<?php
namespace ClouDNS\CloudDNS;

class Record extends BaseLogic
{

    /**
     * load zone list
     * 
     * @param string $z : zone
     * @param int $offset [optional] default 0
     * @param int $number [optional] default 100
     * @example <p>if $number = -1 , then load the valuse which started $offset</p>
     * @throws ClouDNSException
     * @return array : the result array
     */
    public function rec_load_all($z, $offset = 0, $number = 100)
    {
        $param = array(
            'z' => $z,
            'offset' => $offset,
            'number' => $number
        );
        return $this->service()->call(__FUNCTION__, $param);
    }

    /**
     * get record's count of the specify zone
     *
     * @param string $z : zone
     * @throws ClouDNSException
     * @return int : count of records
     */
    public function rec_load_size($z)
    {
        $param = array(
            'z' => $z
        );
        return $this->service()->call(__FUNCTION__, $param) - 0;
    }

    /**
     * Specify zone and rid , get the record in it
     *
     * @param string $z
     * @param int $rid
     * @return array : result array
     */
    public function rec_load($z, $rid)
    {
        $param = array(
            'z' => $z,
            'rid' => $rid
        );
        return $this->service()->call(__FUNCTION__, $param);
    }

    /**
     * add a new dns recored
     *
     * @param string $z
     * @param string $type : for example A
     * @param string $name
     * @param string $content
     * @param string $isp
     * @param int $ttl
     * @throws ClouDNSException
     * @return array : result array
     */
    public function rec_new($z, $type, $name, $content, $isp, $ttl)
    {
        $param = array(
            'z' => $z,
            'type' => $type,
            'name' => $name,
            'content' => $content,
            'isp' => $isp,
            'ttl' => $ttl
        );
        return $this->service()->call(__FUNCTION__, $param);
    }

    /**
     * edit a dns recored
     *
     * @param string $rid
     * @param string $z
     * @param string $type : for example A
     * @param string $name
     * @param string $content
     * @param string $isp
     * @param int $ttl
     * @throws ClouDNSException
     * @return array : result array
     */
    public function rec_edit($z, $rid, $type, $name, $content, $isp, $ttl)
    {
        $param = array(
            'rid' => $rid,
            'z' => $z,
            'type' => $type,
            'name' => $name,
            'content' => $content,
            'isp' => $isp,
            'ttl' => $ttl
        );
        return $this->service()->call(__FUNCTION__, $param);
    }

    /**
     * delete a dns recored
     *
     * @param string $z
     * @param string $rid
     * @throws ClouDNSException
     * @return array : return the content of the record which was deleted
     */
    public function rec_delete($z, $rid)
    {
        $param = array(
            'z' => $z,
            'rid' => $rid
        );
        return $this->service()->call(__FUNCTION__, $param);
    }

    /**
     * delete multi dns recored
     *
     * @param string $z
     * @param array $rids
     * @throws ClouDNSException
     * @return array : return the content of the records which were deleted
     */
    public function bulk_rec_delete($z, array $rids)
    {
        $param = array(
            'z' => $z,
            'rids' => join(',', $rids)
        );
        return $this->service()->call(__FUNCTION__, $param);
    }

    /**
     * delete all dns recored of one name
     *
     * @param string $z
     * @param array $rids
     * @throws ClouDNSException
     * @return array : return the content of the records which were deleted
     */
    public function rec_delete_by_name($z, $name)
    {
        $param = array(
            'z' => $z,
            'name' => $name
        );
        return $this->service()->call(__FUNCTION__, $param);
    }

    /**
     * delete all dns recored of one name
     *
     * @param string $z
     * @param array $records
     * @example one records example : <pre>
     *          array(
     *              array(
     *                  type => "A",
     *                  name => "test1",
     *                  content => "1.2.3.4",
     *                  isp => "tel",
     *                  ttl => 300
     *              ),
     *              array(
     *                  type => "A",
     *                  name => "test2",
     *                  content => "1.2.3.5",
     *                  isp => "tel",
     *                  ttl => 300
     *              )
     *          )
     *          </pre>
     * @throws ClouDNSException
     * @return array : return the content of the records which were added
     */
    public function bulk_rec_new($z, array $records)
    {
        $param = array(
            'z' => $z,
            'records' => json_encode($records)
        );
        return $this->service()->call(__FUNCTION__, $param);
    }

    /**
     * According to the host name search DNS records
     *
     * @param string $z
     * @param string $name
     * @param int $offset
     * @param int $number
     * @return array
     * @example one example return : <pre>
     *          array(
     *              'number' => 1,
     *              'data' => array(
     *                  'ctime' => '2013-11-26',
     *                  'status' => 1,
     *                  'ttl' => 300,
     *                  'prio' => 0,
     *                  'content' => '1.2.3.4',
     *                  'name' => 'test1',
     *                  'zid' => 3,
     *                  'id' => 67311,
     *                  'type' => 'A',
     *                  'isp' => 'tel'
     *              ),
     *              'total' => 2,
     *              'offset' => 0
     *          );
     *          </pre>
     */
    public function rec_load_by_name($z, $name, $offset = null, $number = null)
    {
        $param = array(
            'z' => $z,
            'name' => $name
        );
        ! is_null($offset) && $param['offset'] = $offset;
        ! is_null($number) && $param['number'] = $number;
        return $this->service()->call(__FUNCTION__, $param);
    }

    /**
     * According to the string matching search DNS records
     *
     * @param string $z
     * @param string $name
     * @param int $offset
     * @param int $number
     * @return array
     * @example 
     *          <p>*.game：matchs  abc.game.example.com,	xyz.game.example.com , etc </p>
     *          <p>s*.game: matchs  s1.game.example.com, s2.game.example.com , etc </p>
     *          <p>ddt.*.game: matchs  ddt.x.game.example.com , ddt.y.game.example.com , etc</p>
     * @example one example return : <pre>
     *          array(
     *              'number' => 1,
     *              'data' => array(
     *                  'ctime' => '2013-11-26',
     *                  'status' => 1,
     *                  'ttl' => 300,
     *                  'prio' => 0,
     *                  'content' => '1.2.3.4',
     *                  'name' => 'test1',
     *                  'zid' => 3,
     *                  'id' => 67311,
     *                  'type' => 'A',
     *                  'isp' => 'tel'
     *              ),
     *              'total' => 2,
     *              'offset' => 0
     *          );
     *          </pre>
     */
    public function rec_load_by_prefix($z, $name, $offset = null, $number = null)
    {
        $param = array(
            'z' => $z,
            'name' => $name
        );
        ! is_null($offset) && $param['offset'] = $offset;
        ! is_null($number) && $param['number'] = $number;
        return $this->service()->call(__FUNCTION__, $param);
    }
    
}