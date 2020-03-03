<?php

class Dc_Model_DbProcedure_AddDeviceType extends SJX_Procedure_Abstract
{
    protected $_name = 'AddDeviceType';
    protected $_usePDO = true;

    protected $_fields = [
        'unittype'       => ['type' => 'string', 'name' => 'XUnitType'],
        'devicename'     => ['type' => 'string', 'name' => 'XDeviceName'],
        'nportsnumber'   => ['type' => 'integer', 'name' => 'XNPortsNumber'],
        'pportsnumber'   => ['type' => 'integer', 'name' => 'XPPortsNumber'],
        'cportsnumber'   => ['type' => 'integer', 'name' => 'XCPortsNumber'],
        'scriptid'       => ['type' => 'integer', 'name' => 'XScriptId'],
        'dtgid'          => ['type' => 'integer', 'name' => 'XDTGid'],
        'connecttimeout' => ['type' => 'integer', 'name' => 'XConnectTimeout'],
        'mnemoname'      => ['type' => 'string', 'name' => 'XMnemoName'],
    ];
}