<?php

class Dc_Controller_ConnectionsBackup extends SJX_Controller_Abstract
{
    public function historyAction()
    {
        $unitId = $this->getRequest()->getParam('unitid', null);

        $connectionsBackupService = new Dc_Service_ConnectionsBackup();
        $items = $connectionsBackupService->getFullHistory($unitId);

        $this->_returnJson([
            'success' => true,
            'data'    => $items,
        ]);
    }

    public function makeAction()
    {
        $dcid = $this->getRequest()->getParam('dcid');

        $connectionsBackupService = new Dc_Service_ConnectionsBackup();
        $res = $connectionsBackupService->makeBackup($dcid);

        $this->_returnJson($res);
    }
}