<?php

class Dc_Model_DbTable_DeviceTypes extends SJX_Model_DbTableAbstract
{
    protected $_name = 'DeviceTypes';
    protected $_primary = 'devicetypeid';
    protected $_defOrders = ['unittype' => 'ASC', 'devicename' => 'ASC'];

    protected $_fields = [
        'devicetypeid'   => 'integer',
        'unittype'       => 'string',
        'devicename'     => 'string',
        'portsnumber'    => 'integer',
        'nportsnumber'   => 'integer',
        'pportsnumber'   => 'integer',
        'cportsnumber'   => 'integer',
        'scriptid'       => 'integer',
        'mnemoname'      => 'string',
        'connecttimeout' => 'integer',
    ];
}