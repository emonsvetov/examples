<?php

class Dc_Model_DbProcedure_AddDCConnection extends SJX_Procedure_Abstract
{
    protected $_name = 'AddDCConnection';
    protected $_authority = 'edit_dcunits_connections';
    protected $_usePDO = true;

    protected $_fields = [
        'conntype'        => ['type' => 'int8', 'name' => 'XConnType'],
        'unitid1'         => ['type' => 'integer', 'name' => 'XUnitId1'],
        'unitid2'         => ['type' => 'integer', 'name' => 'XUnitId2'],
        'unit1port'       => ['type' => 'integer', 'name' => 'XUnit1Port'],
        'unit2port'       => ['type' => 'integer', 'name' => 'XUnit2Port'],
        'ethermode'       => ['type' => 'integer', 'name' => 'XEtherMode'],
        'showswitchgraph' => ['type' => 'flag',    'name' => 'XShowSwitchGraph'],
        'uplinkunit'      => ['type' => 'integer', 'name' => 'XUplinkUnit'],
    ];
}