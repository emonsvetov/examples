<?php

use \Zend\ServiceManager\ServiceManager;

class Dc_Bootstrap
{
    public function _initEvents( ServiceManager $serviceManager )
    {
        $eventManager = $serviceManager->get('EventManager');

        if (SJX_Instance::isOffice()) {
            $eventManager->attach('application.client.card.initMenu', function($event){
                $params = $event->getParams();
                $dcConnectionHistoryService = new Dc_Service_DCConnectionHistory();
                $dcConnectionHistoryService->notify($event->getName(), $params);
            }, 15);

            $eventManager->attach('application.client.card.initTabsNew', function($event){
                $params = $event->getParams();
                $cpanelAccountsService = new Dc_Service_CpanelAccounts();
                $cpanelAccountsService->notify($event->getName(), $params);
            }, 50);
        }

        $eventManager->attach('dc-ipmiadmins-changed', function($event){
            $params = $event->getParams();
            $ipmiGatewayService = new Dc_Service_IpmiGateway();
            $ipmiGatewayService->notify($event->getName(), $params);
        });

        $eventManager->attach('dc-ipmiadmins-added', function($event){
            $params = $event->getParams();
            $ipmiGatewayService = new Dc_Service_IpmiGateway();
            $ipmiGatewayService->notify($event->getName(), $params);
        });

        $eventManager->attach('dc-ipmiadmins-deleted', function($event){
            $params = $event->getParams();
            $ipmiGatewayService = new Dc_Service_IpmiGateway();
            $ipmiGatewayService->notify($event->getName(), $params);
        });

        $eventManager->attach('dc-ipmicommands-create', function($event){
            $params = $event->getParams();
            $ipmiGatewayService = new Dc_Service_IpmiGateway();
            $ipmiGatewayService->notify($event->getName(), $params);
        });

        $eventManager->attach('dc-ipmicommands-close', function($event){
            $params = $event->getParams();
            $ipmiGatewayService = new Dc_Service_IpmiGateway();
            $ipmiGatewayService->notify($event->getName(), $params);
        });

        $eventManager->attach('dc-ipmicommands-edit', function($event){
            $params = $event->getParams();
            $ipmiGatewayService = new Dc_Service_IpmiGateway();
            $ipmiGatewayService->notify($event->getName(), $params);
        });
    }
}