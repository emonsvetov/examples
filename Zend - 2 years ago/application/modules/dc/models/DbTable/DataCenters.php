<?php

class Dc_Model_DbTable_DataCenters extends SJX_Model_DbTableAbstract
{
    protected $_name = 'v_aDataCenters';
    protected $_primary = 'dcid';
    protected $_alias = 'dc';
    protected $_defOrders = ['name' => 'ASC'];

    protected $_fields = [
        'name'                => 'string',
        'prefcodeour'         => 'string',
        'prefcodedc'          => 'string',
        'domainofthehost'     => 'string',
        'emailtemplt'         => 'string',
        'dcemail'             => 'string',
        'trafkb'              => 'integer',
        'runeturl'            => 'string',
        'dns1'                => 'string',
        'dns2'                => 'string',
        'dns3'                => 'string',
        'dns4'                => 'string',
        'dns5'                => 'string',
        'showinleftmenu'      => 'flag',
        'sortpos'             => 'integer',
        'notifyonunitrelease' => 'string',
        'unitaccessemails'    => 'string',
    ];
}