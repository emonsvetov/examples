<?php

use Application\modules\dc\models\DataCenters;

class Dc_Controller_IpGroups extends SJX_Controller_Abstract
{
    public function init()
    {
        if (!SJX_Instance::aclIsAllowed('ip_groups_manage')) {
            $this->_accessDined();
        }
    }

    public function indexAction()
    {
        SJX_Instance::getLayout()->include_extjs_4_2 = true;

        $dataCentersModel = new DataCenters();
        $this->view->datacenters = $dataCentersModel->getAll();
    }

    public function viewAction()
    {
        $dcid = $this->getRequest()->getParam('dcid', null);
        $publicip = $this->getRequest()->getParam('publicip', null);

        if($publicip == 'all') {
            $publicip = null;
        }

        $ipGroupsService = new Dc_Service_IpGroups();
        $result = $ipGroupsService->getDataForView($dcid, $publicip);

        $this->_returnJson([
            'success' => true,
            'total'   => $result['total'],
            'data'    => $result['items']
        ]);
    }

    public function addAction()
    {
        $data = SJX_Tool_Extjs::getRequest($this->getRequest());

        $post = $this->getRequest()->getPost();
        $post['data'] = $data;

        $ipGroupsService = new Dc_Service_IpGroups();
        $res = $ipGroupsService->validate($post);

        if ($res['success']) {
            $res = $ipGroupsService->save($post);
        }

        $this->_returnJson([
            'success' => $res['success'],
            'message' => isset($res['errors']) ? join('<br/>', $res['errors']) : $res['error'],
        ]);
    }

    public function deleteAction()
    {
        $data = SJX_Tool_Extjs::getRequest($this->getRequest());
        $res = SJX_Tool_Extjs::deleteModelProc(new Dc_Model_DbProcedure_DeleteIpGroup(), $data, 'ipgpid');

        $this->_returnJson($res);
    }

    public function groupAction()
    {
        $ipgpid = $this->getRequest()->getParam('ipgpid', null);

        $ipGroupsModel = new Dc_Model_IpGroups();
        $ipGroup = $ipGroupsModel->getById($ipgpid);

        $ipGroupDcsService = new Dc_Service_IpGroupDcs();
        $ipGroupDcsItems = $ipGroupDcsService->getDcs($ipgpid);

        $this->_returnJson([
            'success'  => true,
            'ipgpid'   => $ipgpid,
            'gateway1' => $ipGroup['gateway1'],
            'gateway2' => $ipGroup['gateway2'],
            'gateway3' => $ipGroup['gateway3'],
            'gateway4' => $ipGroup['gateway4'],
            'notes'    => $ipGroup['notes'],
            'publicip' => $ipGroup['publicip'],
            'visibletosorm' => $ipGroup['visibletosorm'],
            'dcs'      => $ipGroupDcsItems,
        ]);
    }

    public function editAction()
    {
        $ipgpid = $this->getRequest()->getParam('ipgpid', null);
        $data = SJX_Tool_Extjs::getRequest($this->getRequest());

        $post = $this->getRequest()->getPost();
        $post['data'] = $data;

        $ipGroupsService = new Dc_Service_IpGroups();
        $res = $ipGroupsService->validate($post, $ipgpid);

        if ($res['success']) {
            $res = $ipGroupsService->save($post, $ipgpid);
        }

        $this->_returnJson([
            'success' => $res['success'],
            'message' => isset($res['errors']) ? join('<br/>', $res['errors']) : $res['error'],
        ]);
    }
}