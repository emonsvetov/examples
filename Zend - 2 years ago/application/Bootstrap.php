<?php

use Application\services\Agreements;
use \Zend\ServiceManager\ServiceManager;

class Application_Bootstrap
{
    public function _initEvents(  ServiceManager $serviceManager )
    {
        if (SJX_Instance::isOffice()) {

            $eventManager = $serviceManager->get('EventManager');
            $eventManager->attach('application.client.card.initTabs', function($event){
                $payHistoryService = new Application_Service_PayHistory();
                $params = $event->getParams();
                $payHistoryService->notify($event->getName(), $params);
            }, 80);

            $eventManager->attach('application.client.card.initMenu', function($event){
                $clientService = new Application_Service_Client();
                $params = $event->getParams();
                $clientService->notify($event->getName(), $params);
            }, 95);

            $eventManager->attach('application.client.card.initTabs', function($event){
                $dogovorService = new Application_Service_Dogovor();
                $params = $event->getParams();
                $dogovorService->notify($event->getName(), $params);
            }, 60);

            $eventManager->attach('application.client.card.initTabs', function($event){
                $activityLogService = new Application_Service_ActivityLog();
                $params = $event->getParams();
                $activityLogService->notify($event->getName(), $params);
            }, 50);

            $eventManager->attach('dc.unit.block', function($event){
                $agreementsService = new Agreements();
                $params = $event->getParams();
                $agreementsService->notify($event->getName(), $params);
            }, 100);

            $eventManager->attach('dc.unit.unblock', function($event){
                $agreementsService = new Agreements();
                $params = $event->getParams();
                $agreementsService->notify($event->getName(), $params);
            }, 100);
        }
    }
}