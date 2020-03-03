<?php

class Dc_Model_DbTable_IpGroupDcs extends SJX_Model_DbTableAbstract
{
    protected $_name = 'v_aIP_groups_DCs';
    protected $_alias = 'ipgdc';

    protected $_fields = [
        'ipgpid' => 'integer',
        'dcid'   => 'integer',
    ];
}
