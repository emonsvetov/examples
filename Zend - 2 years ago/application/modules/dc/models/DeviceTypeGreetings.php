<?php

/**
 * Created by SAJAX
 * Comment:
 */
class Dc_Model_DeviceTypeGreetings extends SJX_Model_Abstract
{
    public function __construct()
    {
        $this->_table = new Dc_Model_DbTable_DeviceTypeGreetings();
    }
}