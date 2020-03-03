<?php

class Dc_Model_DbProcedure_AddDCCacti extends SJX_Procedure_Abstract
{
    protected $_name = 'AddDCCacti';
    protected $_usePDO = true;

    protected $_fields = [
        'dcid'            => ['type' => 'integer', 'name' => 'XDCid'],
        'ip'              => ['type' => 'string', 'name' => 'Xip'],
        'login'           => ['type' => 'string', 'name' => 'Xlogin'],
        'passw'           => ['type' => 'string', 'name' => 'Xpassw'],
        'active'          => ['type' => 'flag', 'name' => 'Xactive'],
        'graphtemplateid' => ['type' => 'integer', 'name' => 'XGraphTemplateId'],
    ];
}