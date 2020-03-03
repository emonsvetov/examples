<?php

class Dc_Controller_DeviceTypeGreeting extends SJX_Controller_Abstract
{
    public function init()
    {
        if (!SJX_Instance::aclIsAllowed('edit_switch_greetings')) {
            $this->_accessDined();
        }
    }

    public function indexAction()
    {
        SJX_Instance::getLayout()->include_extjs_4_2 = true;
    }

    public function viewAction()
    {
        $deviceTypeGreetingModel = new Dc_Model_DeviceTypeGreetings();
        $data = $deviceTypeGreetingModel->getAllWithTotal();

        $this->_returnJson([
            'success' => true,
            'data'    => $data['items'],
            'total'   => $data['total'],
        ]);
    }

    public function addAction()
    {
        $data = SJX_Tool_Extjs::getRequest($this->getRequest());
        $res = SJX_Tool_Extjs::saveCard(new Dc_Service_DeviceTypeGreeting(), $data, 'dtgid');

        $this->_returnJson($res);
    }

    public function editAction()
    {
        $data = SJX_Tool_Extjs::getRequest($this->getRequest());
        $res = SJX_Tool_Extjs::saveCard(new Dc_Service_DeviceTypeGreeting(), $data, 'dtgid');

        $this->_returnJson($res);
    }

    public function deleteAction()
    {
        $data = SJX_Tool_Extjs::getRequest($this->getRequest());
        $res = SJX_Tool_Extjs::deleteSrv(new Dc_Service_DeviceTypeGreeting(), $data, 'dtgid');

        $this->_returnJson($res);
    }
}
    