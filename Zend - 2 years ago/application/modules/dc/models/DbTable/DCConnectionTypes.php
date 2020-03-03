<?php

class Dc_Model_DbTable_DCConnectionTypes extends SJX_Model_DbTableAbstract
{
    protected $_name = 'DCConnectionTypes';
    protected $_primary = 'conntype';

    protected $_fields = [
        'conntype' => 'string',
        'name'     => 'string',
    ];
}