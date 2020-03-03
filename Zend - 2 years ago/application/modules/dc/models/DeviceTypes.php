<?php

class Dc_Model_DeviceTypes extends SJX_Model_Abstract
{
    const DELL_DEVICE_TYPE_ID = 16;

    public function __construct()
    {
        $this->_table = new Dc_Model_DbTable_DeviceTypes();
    }

    public function getAllF($filters)
    {
        $select = $this->select()->from(['DeviceTypes' => 'd'], '*');
        $this->whereSelectStr($select, $filters, 'd', 'unittype');

        return $this->db()->fetchAll($select);
    }

    public function getByUnitType($unitType)
    {
        $query = SJX_Db_Query::factory()
            ->addFilter('unittype', '=', $unitType);

        return $this->getAllByQ($query);
    }
}
