<?php
namespace ClouDNS\CloudDNS;

class Zone extends BaseLogic
{

    /**
     * load zone list
     *
     * @param int $offset
     * @param int $number
     * @example <p>if $offset and $number both null, then load all values</p>
     *          <p>if $number = -1 , then load the valuse which started $offset</p>
     * @throws ClouDNSException
     * @return array : the result array
     */
    public function zone_load_multi($offset = null, $number = null)
    {
        $param = array();
        ! is_null($offset) && $param['offset'] = $offset;
        ! is_null($number) && $param['number'] = $number;
        return $this->service()->call(__FUNCTION__, $param);
    }

    /**
     * create a new zone
     *
     * @param string $z
     * @throws ClouDNSException
     * @return string : success info
     */
    public function zone_new($z)
    {
        $param = array(
            'z' => $z
        );
        return $this->service()->call(__FUNCTION__, $param);
    }

    /**
     * get zones info
     *
     * @param array $zones <p>support multi zones query</p>
     * @throws ClouDNSException
     * @return array : zone info array
     */
    public function zone_check(array $zones)
    {
        $param = array(
            'zones' => join(',', $zones)
        );
        return $this->service()->call(__FUNCTION__, $param);
    }

    /**
     * delete a zone and all records belonged to it
     *
     * @param string $z
     * @throws ClouDNSException
     * @return string : zone deletion request has been submitted, please wait for the approvement
     */
    public function zone_delete($z)
    {
        $param = array(
            'z' => $z
        );
        return $this->service()->call(__FUNCTION__, $param);
    }
}