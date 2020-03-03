<?php

/**
 * Created by SAJAX
 */
class Dc_Model_DbTable_Cacti2 extends SJX_Model_DbTableAbstract
{
    protected $_name = 'Cacti2';
    protected $_primary = 'Cacti2Id';
    protected $_alias = 'ct2';

    protected $_fields = [
        'Cacti2Id'       => 'integer',
        'DCid'           => 'integer',
        'UnitId'         => 'integer',
        'PortNum'        => 'integer',
        'CactiLocalPort' => 'integer',
        'SyncResult'     => 'string',
    ];
}