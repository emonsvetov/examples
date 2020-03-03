<?php

class Dc_Model_DbTable_DCUnits extends SJX_Model_DbTableAbstract
{
    protected $_name = 'v_aDCUnits';
    protected $_primary = 'unitid';
    protected $_alias = 'u';
    protected $_queryFileds = [
        'str' => ['codeour', 'codedc'],
        'int' => ['dcid', 'rackid'],
    ];

    protected $_fields = [
        'unitid'             => 'integer',
        'rackid'             => 'integer',
        'dcid'               => 'integer',
        'unittype'           => 'string',
        'devicetypeid'       => 'integer',
        'codecli'            => 'string',
        'netports'           => 'integer',
        'powerports'         => 'integer',
        'conports'           => 'integer',
        'IsBlade'            => 'flag',
        'QtyBlades'          => 'integer',
        'ChassisUnitId'      => 'integer',
        'BladePos'           => 'integer',
        'ipmi'               => 'string',
        'unitn'              => 'integer',
        'unitsize'           => 'integer',
        'codeour'            => 'string',
        'codedc'             => 'string',
        'model'              => 'string',
        'rackside'           => 'string',
        'cfg'                => 'string',
        'os'                 => 'string',
        'ctrl_svc'           => 'string',
        'ctrl_port'          => 'integer',
        'ctrl_prot'          => 'string',
        'ctrl_prot'          => 'string',
        'maintn'             => 'string',
        'whoadmins'          => 'string',
        'purpose'            => 'string',
        'cmnt'               => 'string',
        'dtinstall'          => 'date',
        'firmware'           => 'string',
        'cons_uid'           => 'integer',
        'powerwatts'         => 'integer',
        'servertype'         => 'string',
        'tech_login'         => 'string',
        'tech_pass'          => 'string',
        'swenblpass'         => 'string',
        'ipmilogin'          => 'string',
        'ipmipass'           => 'string',
        //'ismonitoring'       => 'flag',
        'unitusage'          => 'integer',
        'status'             => 'string',
        'assumedblock'       => 'string',
        'lanadmblock'        => 'bool',
        'stategrant'         => 'bool',
        'unit_lastupdnumber' => 'integer',
        /**
         * ManagedIPMI - 'N'/'Y' - визуализация сосотояния доступа.
         * N - неуправляемый доступ (т.е. ipmi открыт для всех)
         * Y - доступ по правилам
         */
        'ManagedIPMI'        => 'string',
        'usbinfo'            => 'string',
        'showlinkshistory'   => 'date',
        'LastReboot'         => 'date',

        /**
         * Поля счетчики, автоматом формируются в базе.
         */
        'QtyFreeNPorts'      => 'integer',
        'QtyFreePPorts'      => 'integer',
        'QtyFreeCPorts'      => 'integer',
        'UseChassisIpmiPort' => 'flag',
        'igtid'              => 'integer',
        'vlan'               => 'integer',
    ];
}