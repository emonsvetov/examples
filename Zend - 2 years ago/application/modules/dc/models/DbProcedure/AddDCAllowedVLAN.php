<?php

class Dc_Model_DbProcedure_AddDCAllowedVLAN extends SJX_Procedure_Abstract
{
    protected $_name = 'AddDCAllowedVLAN';
    protected $_usePDO = true;

    protected $_fields = [
        'dcid'     => ['type' => 'integer', 'name' => 'XDCid'],
        'vlanfrom' => ['type' => 'integer', 'name' => 'XVlanFrom'],
        'vlanto'   => ['type' => 'integer', 'name' => 'XVlanTo'],
        'descr'    => ['type' => 'string',  'name' => 'XDescr'],
    ];
}