<?php

class Dc_Model_DbProcedure_AddDCUAddInfoV extends SJX_Procedure_Abstract
{
    protected $_name = 'AddDCUAddInfoV';
    protected $_usePDO = true;

    protected $_fields = [
        'unitid'     => ['type' => 'integer', 'name' => 'XDCUnitId'],
        'active'     => ['type' => 'flag', 'name' => 'Xactive'],
        'adminlogin' => ['type' => 'string', 'name' => 'Xadminlogin'],
        'adminpass'  => ['type' => 'string', 'name' => 'Xadminpass'],
    ];
}
