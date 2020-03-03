<?php

/**
 * Created by SAJAX
 * Comment:
 */
class Dc_Model_DbProcedure_AddDeviceTypeGreeting extends SJX_Procedure_Abstract
{
    protected $_name = 'AddDeviceTypeGreeting';
    protected $_usePDO = true;

    protected $_fields = [
        'grtuser'    => ['type' => 'string', 'name' => 'XGrtUser'],
        'grtpass'    => ['type' => 'string', 'name' => 'XGrtPass'],
        'grtprompt'  => ['type' => 'string', 'name' => 'XGrtPrompt'],
        'decription' => ['type' => 'string', 'name' => 'XDecription'],
    ];
}