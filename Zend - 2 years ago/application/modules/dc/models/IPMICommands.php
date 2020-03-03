<?php

class Dc_Model_IPMICommands extends SJX_Model_Abstract
{
    public function __construct()
    {
        $this->_table = new Dc_Model_DbTable_IPMICommands();
    }

    public function getSynchCommands($ipmiGwId, $expired = false)
    {
        $exp = new SJX_Db_Exp('(case when a.dtblock<=current year to second then "Y" else "N" end)', 'expired');
        $select = $this->select()
            ->from(['IPMICommands' => 'a'], ['*', $exp])
            ->inner(['v_aDCUnits' => 'u'], 'u.unitid = a.unitid', ['codeour'])
            ->inner(['IPMIGateways' => 'gw'], 'gw.dcid=u.dcid')
            ->where('gw.igtid = u.igtid')
            ->where('gw.ipmi_gw_active = 1')
            ->where('(a.status between 0 and 8)')
            ->where($this->getWhereInt('gw', 'ipmi_gw_id', $ipmiGwId));

        if ($expired) {
            $select->where("a.dtblock<=current year to second");
        }

        $select->order($this->prepareOrders(['a.icid' => 'asc']));

        return $this->db()->fetchAll($select);
    }
}