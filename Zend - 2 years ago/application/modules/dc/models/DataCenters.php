<?php namespace Application\modules\dc\models;

use Application_Model_Clients;
use Buch_Agts_Model_vaClientLinks;
use Buch_Agts_Service_AgreementClasses;
use Dc_Model_DbTable_DataCenters;
use Dc_Model_ServerTypes;
use SJX_Db_Exp;
use SJX_Model_Abstract;

class DataCenters extends SJX_Model_Abstract
{
    const LOCATION_MSK = 'msk';
    const LOCATION_CZ = 'cz';

    public function __construct()
    {
        $this->_table = new Dc_Model_DbTable_DataCenters();
    }

    public function getAllWithoutTest()
    {
        $query = "SELECT * FROM v_aDataCenters WHERE name NOT LIKE 'Тестовый%'";

        return $this->db()->fetchAll($query);
    }

    public function getFastMenuItems()
    {
        $select = $this->select()->from(['v_aDataCenters' => 'dc'], '*')
            ->where($this->getWhereBool('dc', 'showinleftmenu', true))
            ->order(['dc.sortpos' => 'ASC']);

        return $this->db()->fetchAll($select);
    }

    /**
     * @param $dcids
     * @param $type
     *      1 - по выделенным серверам
     *      2 - по размещенным серверам
     *      3 - по выделенным серверам
     *      4 - по всем серверам.
     *
     * @param null $clientId
     * @return mixed
     */
    public function getSpamingClients($dcids, $serverTypes, $clientId = null)
    {
        $selects = [];

        $subsort1 = new SJX_Db_Exp('1', 'subsort');
        $select1 = $this->select()
            ->from(['v_aClients' => 'c'], [
                $subsort1, 'name', 'id', 'yuremail1', 'yuremail2', 'yuremail3', 'yuremail4'])
            ->inner(['v_aAgreements' => 'agmnt'], 'agmnt.customerid = c.id', ['domain'])
            ->inner(['v_aDCUnits' => 'u'], 'u.unitid = agmnt.serverid', ['codecli'])
            ->where($this->getWhereInt('u', 'dcid', $dcids))
            ->where($this->getWhereStr('u', 'servertype', $serverTypes))
            ->where('NOT u.Archived')
            ->where("agmnt.domain != ''")
            ->where('agmnt.status = "O"')
            ->where('c.tarifmode = "' . Application_Model_Clients::TARIFF_MODE_OLD . '"');

        if ($clientId) {
            $select1->where($this->getWhereInt('c', 'id', $clientId));
        }

        $selects[] = $select1;

        if (in_array(Dc_Model_ServerTypes::HOSTING, $serverTypes)) {

            $subsort2 = new SJX_Db_Exp('2', 'subsort');
            $select2 = $this->select()
                ->from(['v_aClients' => 'c'], [
                    $subsort2, 'name', 'id', 'yuremail1', 'yuremail2', 'yuremail3', 'yuremail4'])
                ->inner(['v_aClientLinks' => 'clks'], 'clks.clientid = c.id')
                ->inner(['v_aCPanelAccounts' => 'acpa'], 'acpa.cpaid = clks.objectid')
                ->inner(['v_aClientDomains' => 'cdm'], 'cdm.domainid=acpa.domainid', ['domainname' => 'domain'])
                ->inner(['v_aDCUnits' => 'u'], 'u.unitid = acpa.unitid', ['codecli'])
                ->where($this->getWhereStr('clks', 'status', Buch_Agts_Model_vaClientLinks::STATUS_WORK))
                ->where($this->getWhereInt('clks', 'aclassid', Buch_Agts_Service_AgreementClasses::CLASS_HOSTING))
                ->where($this->getWhereStr('u', 'servertype', Dc_Model_ServerTypes::HOSTING))
                ->where($this->getWhereInt('u', 'dcid', $dcids))
                ->where('c.tarifmode = "' . Application_Model_Clients::TARIFF_MODE_NEW . '"');

            if ($clientId) {
                $select2->where($this->getWhereInt('c', 'id', $clientId));
            }

            $selects[] = $select2;
            $hostingKey = array_search(Dc_Model_ServerTypes::HOSTING, $serverTypes);
            unset($serverTypes[$hostingKey]);
        }

        if (!empty($serverTypes)) {
            $subsort3 = new SJX_Db_Exp('3', 'subsort');
            $select3 = $this->select()
                ->from(['v_aClients' => 'c'], [
                    $subsort3, 'name', 'id', 'yuremail1', 'yuremail2', 'yuremail3', 'yuremail4'])
                ->inner(['v_aClientLinks' => 'clks'], 'clks.clientid = c.id')
                ->where($this->getWhereStr('clks', 'status', Buch_Agts_Model_vaClientLinks::STATUS_WORK))
                ->inner(['v_aDCUnits' => 'u'], 'u.unitid = clks.objectid', [
                    'codecli', 'codeour' => 'domain'])
                ->where($this->getWhereInt('clks', 'aclassid', [
                    Buch_Agts_Service_AgreementClasses::CLASS_DEDICATED,
                    Buch_Agts_Service_AgreementClasses::CLASS_COLOCATION,
                ]))
                ->where($this->getWhereStr('u', 'servertype', $serverTypes))
                ->where($this->getWhereInt('u', 'dcid', $dcids))
                ->where('c.tarifmode = "' . Application_Model_Clients::TARIFF_MODE_NEW . '"');

            if ($clientId) {
                $select3->where($this->getWhereInt('c', 'id', $clientId));
            }
            $selects[] = $select3;
        }

        $select = join(' union all ', $selects);
        $select .= ' ORDER BY 1 ASC, domain ASC';

        return $this->db()->fetchAll($select);
    }
}
