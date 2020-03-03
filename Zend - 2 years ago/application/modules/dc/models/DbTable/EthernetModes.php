<?php

class Dc_Model_DbTable_EthernetModes extends SJX_Model_DbTableAbstract
{
    protected $_name = 'EthernetModes';
    protected $_primary = 'modetype';
    protected $_defOrders = ['modesort' => 'ASC'];

    protected $_fields = [
        'modetype' => 'integer',
        'modesort' => 'integer',
        'modename' => 'string',
    ];
}