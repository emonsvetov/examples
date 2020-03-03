<?php

class Dc_Model_DbProcedure_AddDCRack extends SJX_Procedure_Abstract
{
    protected $_name = 'AddDCRack';
    protected $_usePDO = true;

    protected $_fields = [
        'dcid'      => ['type' => 'integer', 'name' => 'XDCId'],
        'wattlimit' => ['type' => 'integer', 'name' => 'XWattLimit'],
        'unitlimit' => ['type' => 'integer', 'name' => 'XUnitLimit'],
        'delunits'  => ['type' => 'flag',    'name' => 'XDelUnits'],
        'reserve'   => ['type' => 'flag',    'name' => 'XReserve'],
        'virtual'   => ['type' => 'flag',    'name' => 'XVirtual'],
        'name'      => ['type' => 'string',  'name' => 'XName'],
        'dsc'       => ['type' => 'string',  'name' => 'XDsc'],
    ];
}