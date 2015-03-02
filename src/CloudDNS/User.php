<?php
namespace ClouDNS\CloudDNS;

class User extends BaseLogic
{

    /**
     * recreate user's token
     *
     * @param int $offset
     * @param int $number
     * @throws ClouDNSException
     * @return string : user's new tkn
     */
    public function user_edit_token()
    {
        $tkn = $this->service()->call(__FUNCTION__, array());
        /* if recreate success , change local tkn */
        if (! empty($tkn)) {
            Config::changeTkn($tkn);
        }
        return $tkn;
    }

    /**
     * load all log of the user
     *
     * @param int $offset
     * @param int $number
     * @throws ClouDNSException
     * @return array : result array
     */
    public function userlog_load_all($offset = 0, $number = 100)
    {
        $param = array(
            'offset' => $offset,
            'number' => $number
        );
        return $this->service()->call(__FUNCTION__, $param);
    }

    /**
     * load apply zone log
     *
     * @param int $offset
     * @param int $number
     * @throws ClouDNSException
     * @return array : result array
     */
    public function applyhist_load_all($offset = 0, $number = 100)
    {
        $param = array(
            'offset' => $offset,
            'number' => $number
        );
        return $this->service()->call(__FUNCTION__, $param);
    }
}