<?php

use Application\modules\dc\models\DataCenters;

class Dc_Controller_Cacti extends SJX_Controller_Abstract
{
    public function unitTabAction()
    {
        $unitId = $this->getRequest()->getParam('unitid', null);
        $this->view->unitId = $unitId;

        $unitsCardService = new Dc_Service_UnitsCard();
        $this->view->unit = $unitsCardService->getById($unitId);

        $this->view->rraIdHours = Dc_Service_Cacti::HOURS;
        $this->view->rraIdDay = Dc_Service_Cacti::DAY;
        $this->view->rraIdWeek = Dc_Service_Cacti::WEEK;
        $this->view->rraIdMonth = Dc_Service_Cacti::MONTH;
        $this->view->rraIdYear = Dc_Service_Cacti::YEAR;
        $this->view->rraIdDefault = $this->view->rraIdDay;
    }

    public function indexAction()
    {
        if($this->aclIsAllowed('sorm')){
            $this->_redirect([], true, 'office');
        }

        SJX_Instance::getLayout()->include_extjs_4_2 = true;

        $dcModel = new DataCenters();
        $this->view->dcList = $dcModel->getAllWithoutTest();
    }

    public function viewAction()
    {
        $dcCactiModel = new Dc_Model_vaDCCacti();
        $items = $dcCactiModel->getAllWithTotal();

        SJX_Tool_Extjs::prepareItemsDtToGrid($items['items'], ['lastsynch']);

        $this->_returnJson([
            'success' => true,
            'data'    => $items['items'],
            'total'   => $items['total'],
        ]);
    }

    public function addAction()
    {
        $post = $this->getRequest()->getPost();

        $cactiService = new Dc_Service_Cacti();
        $res = $cactiService->save($post);

        $this->_returnJson($res);
    }

    public function editAction()
    {
        $dccid = $this->getRequest()->getParam('dccid');
        $post = $this->getRequest()->getPost();

        $cactiService = new Dc_Service_Cacti();
        $res = $cactiService->save($post, $dccid);

        $this->_returnJson($res);
    }

    public function deleteAction()
    {
        $dccid = $this->getRequest()->getParam('dccid');

        $cactiService = new Dc_Service_Cacti();
        $res = $cactiService->delete($dccid);

        $this->_returnJson($res);
    }

    public function synchAction()
    {
        if (SJX_Instance::aclIsAllowed('run_cacti_synch')) {
            $dccid = $this->getRequest()->getParam('dccid', null);

            $cactiService = new Dc_Service_Cacti();
            $results = $cactiService->synch($dccid);

            $this->_returnJson([
                'dccid'   => $dccid,
                'success' => true,
                'result'  => $results[$dccid],
            ]);
        } else {
            //$this->_accessDined();
        }
    }

    public function loadDumpAction()
    {
        $dcid = $this->getRequest()->getParam('dcid', null);
        $unitId = $this->getRequest()->getParam('unitid', null);

        $cactiService = new Dc_Service_Cacti();
        $result = $cactiService->loadDump($dcid, $unitId);

        $this->_returnJson([
            'success' => true,
            'result'  => $result,
        ]);
    }

    public function loadGraphsInfoAction()
    {
        $unitId = $this->getRequest()->getParam('unitid', null);
        if (!$unitId) {
            $this->_returnJson([
                'success' => false,
                'error'   => _('Не задан идентификатор юнита')]);
        }

        $cactiService = new Dc_Service_Cacti();
        $items = $cactiService->getGraphsInfo($unitId);

        $this->_returnJson([
            'success' => true,
            'error'   => '',
            'data'    => $items,
        ]);
    }

    public function graphAction()
    {
        $unitId = $this->getRequest()->getParam('unitid', null);
        $connId = $this->getRequest()->getParam('connid', null);
        $dtFrom = $this->getRequest()->getParam('dt_from', null);
        $dtTo = $this->getRequest()->getParam('dt_to', null);
        $rraId = $this->getRequest()->getParam('rra_id', null);

        if (!intval($rraId)) {
            $rraId = null;
        }

        $cactiService = new Dc_Service_Cacti();
        $image = $cactiService->getGraphPortTraffic($unitId, $connId, $dtFrom, $dtTo, $rraId);

        if ($image) {
            header('Content-Type: image/png');
            echo $image;
        } else {
            header('Content-Type: image/gif');
            echo file_get_contents('https://office.atlex.ru/Images/notraf.gif');
        }
        exit;
    }

    public function compareAction()
    {
        $unitId = $this->getRequest()->getParam('unitid', null);

        $cactiService = new Dc_Service_Cacti();
        $dump = $cactiService->loadDump(null, $unitId);

        $unitsCardService = new Dc_Service_UnitsCard();
        $unit = $unitsCardService->getById($unitId);

        $deviceTypePortsService = new Dc_Service_DeviceTypePorts();
        $ports = $deviceTypePortsService->getPorts($unit['devicetypeid']);


        $this->_returnJson([
            'success' => true,
            'dump'    => $dump,
            'ports'   => $ports,
        ]);
    }
}