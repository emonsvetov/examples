<?php

/**
 * Created by SAJAX
 * Comment:
 */
class Dc_Model_DbTable_DeviceTypeGreetings extends SJX_Model_DbTableAbstract
{
    protected $_name = 'DeviceTypeGreetings';
    protected $_primary = 'dtgid';
    protected $_alias = 'dtg';
    protected $_defOrders = ['dtg.dtgid' => 'ASC'];

    protected $_fields = [
        'dtgid'      => 'integer',
        'grtuser'    => 'string',
        'grtpass'    => 'string',
        'grtprompt'  => 'string',
        'decription' => 'string',
    ];
}