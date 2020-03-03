<?php

class Dc_Model_IPMIAccessIPs extends SJX_Model_Abstract
{
    public function __construct()
    {
        $this->_table = new Dc_Model_DbTable_IPMIAccessIPs();
    }

    public function getAllForCron($ipmiGwId)
    {
        $select = $this->select()
            ->from(['IPMIAccessIPs' => 'a'], '*')
            ->inner(['IPMICommands' => 'ic'], 'ic.alid = a.alid')
            ->inner(['v_aDCUnits' => 'u'], 'u.unitid = ic.unitid ')
            ->inner(['IPMIGateways' => 'gw'], 'gw.dcid=u.dcid')
            ->where('gw.ipmi_gw_active = 1')
            ->where('gw.igtid = u.igtid')
            ->where('(ic.status between 0 and 8)')
            ->where($this->getWhereInt('gw', 'ipmi_gw_id', $ipmiGwId));

        return $this->db()->fetchAll($select);
    }
}
