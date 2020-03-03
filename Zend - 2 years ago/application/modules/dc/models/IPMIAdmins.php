<?php

class Dc_Model_IPMIAdmins extends SJX_Model_Abstract
{
    public function __construct()
    {
        $this->_table = new Dc_Model_DbTable_IPMIAdmins();
    }

    public function getAllByDcid($dcid)
    {
        $select = $this->select()->from(['IPMIAdmins' => 'a'], ['ip', 'wildcard', 'netmask'])
            ->where($this->getWhereInt('a', 'dcid', $dcid))
            ->order(['a.ip' => 'asc']);

        return $this->db()->fetchAll($select);
    }
}