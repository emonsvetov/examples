<?php

class Dc_Model_IpGroups extends SJX_Model_Abstract
{
    public function __construct()
    {
        $this->_table = new Dc_Model_DbTable_IpGroups();
    }

    public function getAllF($filters, $limits = null, $orders = [], $kind = 1)
    {
        $defOrder = [
            'a.baseip1'  => 'ASC', 'a.baseip2' => 'ASC', 'a.baseip3' => 'ASC', 'a.baseip4' => 'ASC',
            'a.mask1'    => 'ASC', 'a.mask2' => 'ASC', 'a.mask3' => 'ASC', 'a.mask4' => 'ASC',
            'a.gateway1' => 'ASC', 'a.gateway2' => 'ASC', 'a.gateway3' => 'ASC', 'a.gateway4' => 'ASC',
        ];

        $select = $this->select()->from(['v_aIP_groups' => 'a'], '*');

        $this->whereSelectInt($select, $filters, 'a', 'dcid');

        $select->order($this->prepareOrders($orders, $defOrder))
            ->limit($this->prepareLimits($limits));

        return $this->db()->fetchAll($select);
    }

    /**
     * @param null $dcid
     * @param bool $public true - только публичные, false - серные, null - все
     * @return array
     */
    public function getAllByDcid($dcid = null, bool $public = null)
    {
        $select = $this->select()->from(['v_aIP_groups' => 'a'], '*');
        if ($dcid) {

            $select->where('a.ipgpid IN (select ipgdc.ipgpid from v_aIP_groups_DCs as ipgdc WHERE ipgdc.dcid = ' . $dcid . ")");

            if($public === true) {
               $select->where('a.PublicIP');
            } else if($public === false) {
               $select->where('not a.PublicIP');
            }
        }

        $defOrder = [
            'a.baseip1'  => 'ASC', 'a.baseip2' => 'ASC', 'a.baseip3' => 'ASC', 'a.baseip4' => 'ASC',
            'a.mask1'    => 'ASC', 'a.mask2' => 'ASC', 'a.mask3' => 'ASC', 'a.mask4' => 'ASC',
            'a.gateway1' => 'ASC', 'a.gateway2' => 'ASC', 'a.gateway3' => 'ASC', 'a.gateway4' => 'ASC',
        ];
        $select->order($this->prepareOrders($defOrder));

        return $this->fetchAllWithTotal($select);
    }

    public function countFreeIpGroups($dcid = null)
    {
        $select = 'select g.ipgpid, count(*) as free from v_aIP_groups g, v_aIP i
                    where g.IPGpId=i.IPGpId and i.UnitId is NULL';
        if ($dcid) {
            $select .= ' AND g.ipgpid IN (select ipgdc.ipgpid from v_aIP_groups_DCs as ipgdc WHERE ipgdc.dcid = ' . $dcid . ")";
        }
        $select .= ' group by 1';

        return $this->db()->fetchAll($select);
    }
}
