<?php

class Dc_Controller_Connections extends SJX_Controller_Abstract
{
    public function unitTabAction()
    {
        $unitId = $this->getRequest()->getParam('unitid', null);
        $this->view->unitId = $unitId;

        $connectionTypesModel = new Dc_Model_DCConnectionTypes();
        $this->view->connTypes = $connectionTypesModel->getAll();

        $ethernetModesService = new Dc_Service_EthernetModes();
        $this->view->ethernetModes = $ethernetModesService->getAll();

        $unitsCardService = new Dc_Service_UnitsCard();
        $this->view->unitPorts = $unitsCardService->getUnitPorts($unitId);
        $this->view->unit = $unitsCardService->getById($unitId, true);

        $unitEntity = new Dc_Entity_Unit($this->view->unit);
        $lastBackup = [];
        if ($unitEntity->isShowLinksHistory()) {
            $connectionsBackupService = new Dc_Service_ConnectionsBackup();
            $lastBackup = $connectionsBackupService->getLastBackup($unitId);
        }
        $this->view->lastBackup = $lastBackup;
    }

    public function addAction()
    {
        if ($this->aclIsAllowed('edit_dcunits_connections')) {
            $post = $this->getRequest()->getPost();

            $unitConnectionsService = new Dc_Service_UnitConnections();
            $res = $unitConnectionsService->save($post);

            $this->_returnJson([
                'success' => $res['success'],
                'errors'  => array_values($res['errors']),
            ]);
        }
    }

    public function editAction()
    {
        if ($this->aclIsAllowed('edit_dcunits_connections')) {
            $connId = $this->getRequest()->getParam('connid');
            $post = $this->getRequest()->getPost();

            $unitConnectionsService = new Dc_Service_UnitConnections();
            $res = $unitConnectionsService->save($post, $connId);

            $this->_returnJson([
                'success' => $res['success'],
                'errors'  => array_values($res['errors']),
            ]);
        }
    }

    public function viewAction()
    {
        if ($this->aclIsAllowed('view_dcunits_connections')) {
            $unitId = $this->getRequest()->getParam('unitid', null);

            $unitConnectionsService = new Dc_Service_UnitConnections();
            $connections = $unitConnectionsService->getAllConnections($unitId);

            $this->_returnJson([
                'success' => true,
                'data'    => $connections,
                'total'   => count($connections),
            ]);
        }
    }

    public function deleteAction()
    {
        if ($this->aclIsAllowed('edit_dcunits_connections')) {
            $data = $this->getRequest()->getPost();
            $res = SJX_Tool_Extjs::deleteSrv(new Dc_Service_UnitConnections(), $data, 'connid');
            $this->_returnJson($res);
        }
    }

    public function connectionsAction()
    {
        $items = [];

        if ($this->aclIsAllowed('view_dcunits_connections')) {
            $unitId = $this->getRequest()->getParam('unitid', null);

            $unitConnectionsService = new Dc_Service_UnitConnections();
            $items = $unitConnectionsService->getNetConnections($unitId, false, true);
        }

        $this->_returnJson([
            'success' => true,
            'data'    => $items,
            'total'   => count($items),
        ]);
    }

    /**
     * Получения статуса порта очереди
     */
    public function getStateAction()
    {
        if($this->aclIsAllowed('view_dcunits_connections')) {

            $connection_id = $this->getRequest()->getParam('connid', null);
            $untid = $this->getRequest()->getParam('untid', null);

            $unitConnectionsService = new Dc_Service_UnitConnections();
            $task = $unitConnectionsService->getStatusNetPortByConnId($connection_id, $untid);

            $this->_returnJson([
                'success' => true,
                'task'    => $task,
            ]);
        }
    }

    public function connectAction()
    {
        if($this->aclIsAllowed('view_dcunits_connections')) {

            $connection_id = $this->getRequest()->getParam('connid', null);
            $untid = $this->getRequest()->getParam('untid', null);

            $unitConnectionsService = new Dc_Service_UnitConnections();
            $task = $unitConnectionsService->connectNetPortByConnId($connection_id, $untid);

            $this->_returnJson([
                'success' => true,
                'task'    => $task,
            ]);
        }
    }

    public function disconnectAction()
    {
        if($this->aclIsAllowed('view_dcunits_connections')) {

            $connection_id = $this->getRequest()->getParam('connid', null);
            $untid = $this->getRequest()->getParam('untid', null);

            $unitConnectionsService = new Dc_Service_UnitConnections();
            $task = $unitConnectionsService->disconnectNetPortByConnId($connection_id, $untid);

            $this->_returnJson([
                'success' => true,
                'task'    => $task,
            ]);
        }
    }

    public function restartAction()
    {
        if($this->aclIsAllowed('view_dcunits_connections')) {

            $connection_id = $this->getRequest()->getParam('connid', null);
            $untid = $this->getRequest()->getParam('untid', null);

            $unitConnectionsService = new Dc_Service_UnitConnections();
            $task = $unitConnectionsService->restartNetPortByConnId($connection_id, $untid);

            $this->_returnJson([
                'success' => true,
                'task'    => $task,
            ]);
        }
    }

    public function loadMacAction()
    {
        if ($this->aclIsAllowed('view_dcunits_connections')) {
            $connId = $this->getRequest()->getParam('connid', null);

            $unitConnectionsService = new Dc_Service_UnitConnections();
            $res = $unitConnectionsService->getMacTables($connId);

            $this->_returnJson($res);
        }
    }
}