<?php

class Dc_Controller_CpanelAccounts extends SJX_Controller_Abstract
{
    public function clientTabAction()
    {
        $this->view->clientId = $this->getRequest()->getParam('clientid', null);

        $clientDomainsModel = new Domain_Model_vaClientDomains();
        $this->view->domainItems = $clientDomainsModel->getAllByClientId($this->view->clientId);

        $vaCPanelTariffsModel = new Dc_Model_vaCPanelTariffs();
        $this->view->cPanelTariffs = $vaCPanelTariffsModel->getAll();

        $vaCPanelAccountsModel = new Dc_Model_vaCPanelAccounts();
        $this->view->cPanelStatuses = $vaCPanelAccountsModel->getStatuses();

        $clientService = new Application_Service_Client();
        $this->view->client = $clientService->getById($this->view->clientId);
    }

    public function viewAction()
    {
        $query = SJX_Tool_Controller::prepareQuery($this, ['unitid', 'clientid', 'status']);

        $vaCPanelAccountsModel = new Dc_Model_vaCPanelAccounts();
        $data = $vaCPanelAccountsModel->getAllView($query);

        $this->_returnJson([
            'success' => true,
            'data'    => $data['items'],
            'total'   => $data['total'],
        ]);
    }

    public function searchLinksAction()
    {
        $clientId = $this->getRequest()->getParam('clientid', null);

        $vaCPanelAccountsModel = new Dc_Model_vaCPanelAccounts();
        $items = $vaCPanelAccountsModel->searchLinks($clientId);

        $this->_returnJson([
            'success' => true,
            'data'    => $items,
        ]);
    }

    public function addAction()
    {
        $clientId = $this->getRequest()->getParam('clientid', null);

        if ($this->getRequest()->isPost()) {
            $post = $this->getRequest()->getPost();
            $post['status'] = Dc_Service_CpanelAccounts::STATUS_NEW;
            $post['clientid'] = $clientId;

            $cpanelAccountsService = new Dc_Service_CpanelAccounts();
            $res = $cpanelAccountsService->add($post);

            $this->_returnJson([
                'success' => $res['success'],
                'errors'  => array_values($res['errors']),
                'cpaid'   => $res['cpaid'],
            ]);
        }
    }

    public function changePasswAction()
    {
        $cpaid = $this->getRequest()->getParam('cpaid', null);
        $password = $this->getRequest()->getParam('password', null);
        $untid = $this->getRequest()->getParam('untid', null);

        $cpanelAccountsService = new Dc_Service_CpanelAccounts();
        $res = $cpanelAccountsService->changePassword($cpaid, $password, $untid);

        $this->_returnJson($res);
    }

    public function changeTariffAction()
    {
        $cpaid = $this->getRequest()->getParam('cpaid', null);

        if ($this->getRequest()->isPost()) {
            $post = $this->getRequest()->getPost();
            $untid = $this->getRequest()->getParam('untid', null);

            $cpanelAccountsService = new Dc_Service_CpanelAccounts();
            $res = $cpanelAccountsService->changeTariff($cpaid, $post['cptid'], $untid);

            $this->_returnJson($res);
        }
    }

    public function changeUnitAction()
    {
        $cpaid = $this->getRequest()->getParam('cpaid', null);

        if ($this->getRequest()->isPost()) {
            $post = $this->getRequest()->getPost();
            $post['cpaid'] = $cpaid;

            $cpanelAccountsService = new Dc_Service_CpanelAccounts();
            $res = $cpanelAccountsService->changeUnit($post);

            $this->_returnJson([
                'success' => $res['success'],
                'errors'  => array_values($res['errors'])
            ]);
        }
    }

    public function deleteAction()
    {
        $cpaid = $this->getRequest()->getParam('cpaid', null);
        $untid = $this->getRequest()->getParam('untid', null);

        $cpanelAccountsService = new Dc_Service_CpanelAccounts();
        $res = $cpanelAccountsService->delete($cpaid, $untid);

        $this->_returnJson($res);
    }

    public function cardAction()
    {
        SJX_Instance::getLayout()->include_extjs_4_2 = true;

        $cpaid = $this->getRequest()->getParam('cpaid', null);

        $vaCPanelAccountsModel = new Dc_Model_vaCPanelAccounts();
        $this->view->cPanelStatuses = $vaCPanelAccountsModel->getStatuses();

        $cpAccount = $vaCPanelAccountsModel->getById($cpaid);
        $this->view->cpAccount = $cpAccount;

        if ($cpAccount['unitid']) {
            $dcUnitsModel = new Dc_Model_DCUnits();
            $this->view->unit = $dcUnitsModel->getById($cpAccount['unitid']);
        }

        $vaClientLinksModel = new Buch_Agts_Service_ClientLinks();
        $this->view->link = $vaClientLinksModel->getLink($cpaid, Buch_Agts_Service_AgreementClasses::CLASS_HOSTING);

        $clientService = new Application_Service_Client();
        $this->view->client = $clientService->getById($this->view->cpAccount['clientid']);

        $vaCPanelTariffsModel = new Dc_Model_vaCPanelTariffs();
        $this->view->cPanelTariffs = $vaCPanelTariffsModel->getAll();

        $clientDomainsModel = new Domain_Model_vaClientDomains();
        $this->view->domain = $clientDomainsModel->getById($this->view->cpAccount['domainid']);
    }

    public function synchAction()
    {
        $cpaid = $this->getRequest()->getParam('cpaid', null);
        if ($this->getRequest()->isPost()) {
            $password = $this->getRequest()->getParam('password', null);
            $email = $this->getRequest()->getParam('email', null);
            $untid = $this->getRequest()->getParam('untid', null);

            $cpanelAccountsService = new Dc_Service_CpanelAccounts();
            $res = $cpanelAccountsService->synch($cpaid, $password, $email, $untid);

            $this->_returnJson($res);
        }
    }

    public function lockAction()
    {
        $cpaid = $this->getRequest()->getParam('cpaid', null);
        $reason = $this->getRequest()->getParam('reason', null);

        if ($cpaid) {
            $cpanelAccountsService = new Dc_Service_CpanelAccounts();
            $res = $cpanelAccountsService->lock($cpaid, $reason);

            $this->_returnJson([
                'success' => $res['success'],
                'errors'  => array_values($res['errors']),
            ]);
        }
    }

    public function unlockAction()
    {
        $cpaid = $this->getRequest()->getParam('cpaid', null);

        $cpanelAccountsService = new Dc_Service_CpanelAccounts();
        $res = $cpanelAccountsService->unlock($cpaid);

        $this->_returnJson([
            'success' => $res['success'],
            'errors'  => array_values($res['errors']),
        ]);
    }

    public function notifyAction()
    {
        $cpaid = $this->getRequest()->getParam('cpaid', null);
        $type = $this->getRequest()->getParam('type', null);

        if ($this->getRequest()->isPost()) {
            $post = $this->getRequest()->getPost();

            $cpanelAccountsService = new Dc_Service_CpanelAccounts();
            if ($type == Dc_Service_CpanelAccounts::TYPE_LOCK) {
                $res = $cpanelAccountsService->sendLockNotify($post);
            } else {
                $vaCPanelAccountsModel = new Dc_Model_vaCPanelAccounts();
                $cpAccount = $vaCPanelAccountsModel->getById($post['cpaid']);

                $clientsService = new Application_Service_Client();
                $client = $clientsService->getById($cpAccount['clientid']);

                $serviceFilials = new Application_Service_Filials();
                $filial = $serviceFilials->getById($client['fsid']);

                $options = [
                    'bcc'          => $post['bcc'],
                    'useSignature' => false,
                    'smtp'         => $filial['smtpserver'],
                ];

                $res = $cpanelAccountsService->sendPostMail($post, [], $options);
            }

            $this->_returnJson([
                'success' => $res['success'],
                'errors'  => array_values($res['errors']),
            ]);
        } else {
            $cpanelAccountsService = new Dc_Service_CpanelAccounts();
            $notify = $cpanelAccountsService->getNotify($cpaid, $type);
            $notify['success'] = true;
            $this->_returnJson($notify);
        }
    }
}