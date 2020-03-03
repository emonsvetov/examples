<?php

/**
 * Created by SAJAX
 */
class Dc_Model_DbTable_CactiSynchDump extends SJX_Model_DbTableAbstract
{
    protected $_name = 'CactiSynchDump';
    protected $_primary = 'dcid';
    protected $_alias = 'csd';

    protected $_fields = [
        'DCid'           => 'integer',
        'CactiUnitName'  => 'string',
        'CactiPortName'  => 'string',
        'CactiLocalPort' => 'string',
        'UnitId'         => 'integer',
        'SyncResult'     => 'string',
    ];
}