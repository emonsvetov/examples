<?php

class Dc_Model_Cacti extends SJX_Model_Abstract
{
    public function __construct()
    {
        $this->_table = new Dc_Model_DbTable_Cacti();
    }

    public function getCactiLocalPort($unitid, $portNum)
    {
        $pdo = $this->db()->getConnection();
        $select = $this->select()->from(['cacti' => 'cti'], ['cacti_localport'])
            ->where('cti.unitid = ' . (int)$unitid)
            ->where('cti.cacti_unitport = ' . $pdo->quote($portNum));

        return $this->db()->fetchOne($select);
    }
}
