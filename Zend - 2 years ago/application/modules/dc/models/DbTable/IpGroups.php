<?php

class Dc_Model_DbTable_IpGroups extends SJX_Model_DbTableAbstract
{
    protected $_name = 'v_aIP_groups';
    protected $_primary = 'ipgpid';
    protected $_alias = 'vipg';

    protected $_defOrders = [
        'vipg.baseip1'  => 'ASC', 'vipg.baseip2' => 'ASC', 'vipg.baseip3' => 'ASC', 'vipg.baseip4' => 'ASC',
        'vipg.mask1'    => 'ASC', 'vipg.mask2' => 'ASC', 'vipg.mask3' => 'ASC', 'vipg.mask4' => 'ASC',
        'vipg.gateway1' => 'ASC', 'vipg.gateway2' => 'ASC', 'vipg.gateway3' => 'ASC', 'vipg.gateway4' => 'ASC',
    ];

    protected $_fields = [
        'ipgpid'   => 'integer',
        'baseip1'  => 'integer',
        'baseip2'  => 'integer',
        'baseip3'  => 'integer',
        'baseip4'  => 'integer',
        'numofips' => 'integer',
        'mask1'    => 'integer',
        'mask2'    => 'integer',
        'mask3'    => 'integer',
        'mask4'    => 'integer',
        'gateway1' => 'integer',
        'gateway2' => 'integer',
        'gateway3' => 'integer',
        'gateway4' => 'integer',
        'publicip' => 'integer',
        'visibletosorm' => 'integer',
        'updateid' => 'integer',
        'notes'    => 'string',
    ];
}
