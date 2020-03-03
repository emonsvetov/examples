<?php

class Dc_Model_DbTable_ControlPanels extends SJX_Model_DbTableAbstract
{
    protected $_name = 'ControlPanels';
    protected $_primary = 'paneltype';

    protected $_fields = [
        'paneltype' => 'string',
        'name'      => 'string',
    ];
}