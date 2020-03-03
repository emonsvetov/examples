<?php

class Dc_Controller_ConnectionHistory extends SJX_Controller_Abstract
{
    public function indexAction()
    {
        SJX_Instance::getLayout()->include_extjs_4_2 = true;
        $clientId = $this->getRequest()->getParam('clientid', null);

        $clientService = new Application_Service_Client();
        $this->view->client = $clientService->getById($clientId);

        $ethernetModesService = new Dc_Service_EthernetModes();
        $this->view->ethernetModes = $ethernetModesService->getAll();
    }

    public function viewAction()
    {
        $clientId = $this->getRequest()->getParam('clientid', null);
        $dtCreated = $this->getRequest()->getParam('dtcreated', null);
        $dtEnded = $this->getRequest()->getParam('dtended', null);
        $etherMode = $this->getRequest()->getParam('ethermode', null);

        $dcConnectionHistoryService = new Dc_Service_DCConnectionHistory();
        $items = $dcConnectionHistoryService->getReportData($clientId, $dtCreated, $dtEnded, $etherMode);

        $this->_returnJson([
            'success' => true,
            'data'    => $items,
            'total'   => count($items),
        ]);
    }

    public function speedReportAction()
    {
        SJX_Instance::getLayout()->include_extjs_4_2 = true;

        $clientId = $this->getRequest()->getParam('clientid', null);

        $clientService = new Application_Service_Client();
        $this->view->client = $clientService->getById($clientId);

        $ethernetModesService = new Dc_Service_EthernetModes();
        $this->view->ethernetModes = $ethernetModesService->getAll();
    }

    public function speedReportViewAction()
    {
        $clientId = $this->getRequest()->getParam('clientid', null);
        $dtCreated = $this->getRequest()->getParam('dtcreated', null);
        $dtEnded = $this->getRequest()->getParam('dtended', null);

        $dcConnectionHistoryService = new Dc_Service_DCConnectionHistory();
        $items = $dcConnectionHistoryService->calcSpeedReport($clientId, $dtCreated, $dtEnded);

        $this->_returnJson([
            'success' => true,
            'data'    => $items,
            'total'   => count($items),
        ]);
    }

    public function downloadAction()
    {
        $clientId = $this->getRequest()->getParam('clientid', null);
        $dtCreated = $this->getRequest()->getParam('dtcreated', null);
        $dtEnded = $this->getRequest()->getParam('dtended', null);
        $etherMode = $this->getRequest()->getParam('ethermode', null);

        $dcConnectionHistoryService = new Dc_Service_DCConnectionHistory();
        $txt = $dcConnectionHistoryService->createTxtReport($clientId, $dtCreated, $dtEnded, $etherMode);

        header("Content-Type: text/html; charset=cp1251");
        header('Content-Disposition: attachment; filename=connections-report-' . SJX_Tool_Datetime::intToDate(time()) . '.txt');
        echo $txt;
        exit;
    }
}
    