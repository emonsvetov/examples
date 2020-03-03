<?php

class Dc_Model_DbProcedure_AddEthernetMode extends SJX_Procedure_Abstract
{
    protected $_name = 'AddEthernetMode';
    protected $_usePDO = true;

    protected $_fields = [
        'modesort' => ['type' => 'integer', 'name' => 'XModeSort'],
        'modename' => ['type' => 'string', 'name' => 'XModeName'],
    ];
}