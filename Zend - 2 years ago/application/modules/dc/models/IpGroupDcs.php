<?php

class Dc_Model_IpGroupDcs extends SJX_Model_Abstract
{
    public function __construct()
    {
        $this->_table = new Dc_Model_DbTable_IpGroupDcs();
    }

    public function getAllByIpGroupId($ipgrid)
    {
        $query = SJX_Db_Query::factory()
            ->addFilter('ipgpid', '=', $ipgrid);

        return $this->getAllByQ($query);
    }
}