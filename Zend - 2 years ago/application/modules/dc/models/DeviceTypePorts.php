<?php

class Dc_Model_DeviceTypePorts extends SJX_Model_Abstract
{
    public function __construct()
    {
        $this->_table = new Dc_Model_DbTable_DeviceTypePorts();
    }

    public function getAllByDeviceTypeId($deviceTypeId)
    {
        $select = $this->select()->from(['DeviceTypePorts' => 'dtp'], '*')
            ->where($this->getWhereInt('dtp', 'devicetypeid', $deviceTypeId))
            ->order(['dtp.PortNum' => 'ASC']);

        return $this->db()->fetchAll($select);
    }
}