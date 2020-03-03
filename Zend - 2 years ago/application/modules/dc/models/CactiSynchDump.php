<?php

/**
 * Created by SAJAX
 * Comment:
 */
class Dc_Model_CactiSynchDump extends SJX_Model_Abstract
{
    public function __construct()
    {
        $this->_table = new Dc_Model_DbTable_CactiSynchDump();
    }

    public function loadDump($dcid = null, $unitId = null)
    {
        $select = $this->select()->from(['CactiSynchDump' => 'csd'], '*');

        if ($dcid) {
            $select->where($this->getWhereInt('csd', 'dcid', $dcid));
        }
        if ($unitId) {
            $select->where($this->getWhereInt('csd', 'unitid', $unitId));
        }

        return $this->db()->fetchAll($select);
    }
}