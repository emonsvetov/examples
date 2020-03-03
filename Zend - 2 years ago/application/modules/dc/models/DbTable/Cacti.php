<?php

class Dc_Model_DbTable_Cacti extends SJX_Model_DbTableAbstract
{
    protected $_name = 'cacti';
    protected $_primary = 'cacti_id';

    protected $_fields = [
        'unitid'            => 'integer',
        'cacti_unitport'    => 'string',
        'cacti_unitportnum' => 'integer',
        'cacti_localport'   => 'integer',
    ];
}