<?php

class Dc_Model_DbTable_DeviceTypePorts extends SJX_Model_DbTableAbstract
{
    protected $_name = 'DeviceTypePorts';
    protected $_primary = '';

    protected $_fields = [
        'devicetypeid' => 'integer',
        'PortNum'      => 'integer',
        'PortName'     => 'string',
        'PortLabel'    => 'string',
    ];
}