<?php
namespace ClouDNS\CloudDNS;

abstract class BaseLogic
{
    private $serviceObj = null;

    protected function service()
    {
        if (empty($this->serviceObj)) {
            $this->serviceObj = new Service();
        }
        return $this->serviceObj;
    }
}