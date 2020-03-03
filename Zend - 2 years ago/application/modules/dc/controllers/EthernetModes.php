<?php

class Dc_Controller_EthernetModes extends SJX_Controller_Abstract
{
    public function indexAction()
    {
        SJX_Instance::getLayout()->include_extjs_4_2 = true;
    }

    public function viewAction()
    {
        $ethernetModesModel = new Dc_Model_EthernetModes();
        $data = $ethernetModesModel->getAllWithTotal();

        $this->_returnJson([
            'success' => true,
            'data'    => $data['items'],
            'total'   => $data['total'],
        ]);
    }

    public function addAction()
    {
        $data = SJX_Tool_Extjs::getRequest($this->getRequest());
        $res = SJX_Tool_Extjs::saveSrv(new Dc_Service_EthernetModes(), $data, 'modetype');

        $this->_returnJson($res);
    }

    public function editAction()
    {
        $data = SJX_Tool_Extjs::getRequest($this->getRequest());
        $res = SJX_Tool_Extjs::saveSrv(new Dc_Service_EthernetModes(), $data, 'modetype');

        $this->_returnJson($res);
    }

    public function deleteAction()
    {
        $data = SJX_Tool_Extjs::getRequest($this->getRequest());
        $res = SJX_Tool_Extjs::deleteSrv(new Dc_Service_EthernetModes(), $data, 'modetype');

        $this->_returnJson($res);
    }
}