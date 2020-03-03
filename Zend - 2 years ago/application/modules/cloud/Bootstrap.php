<?php
/**
 * Created by PhpStorm.
 * User: e.monsvetov
 * Date: 27/04/2018
 * Time: 15:09
 */

use \Zend\ServiceManager\ServiceManager;

class Cloud_Bootstrap
{
    public function _initFactories( ServiceManager $serviceManager )
    {
        $serviceManager->setFactory(Cloud_Service_ChOsMetricsReplicaFiles::class,
                                    Cloud_Factory_ClickHouse::class );

        $serviceManager->setFactory(Cloud_Service_ChOsMetricsReplica::class,
                                    Cloud_Factory_ClickHouse::class );

        $serviceManager->setFactory(Cloud_Service_chBillingMetricsReplica::class,
                                    Cloud_Factory_ClickHouse::class );

        $serviceManager->setFactory(Cloud_Service_Clouds::class,
                                    Cloud_Factory_CloudsService::class );

        $serviceManager->setFactory(Cloud_Service_OsIdentity::class,
                                    Cloud_Factory_OpenStack::class );

        $serviceManager->setFactory(Cloud_Service_OsCompute::class,
                                    Cloud_Factory_OpenStack::class );

        $serviceManager->setFactory(Cloud_Service_OsNetworking::class,
                                    Cloud_Factory_OpenStack::class );

        $serviceManager->setFactory(Cloud_Service_OsBlockStorage::class,
                                    Cloud_Factory_OpenStack::class );

        $serviceManager->setFactory(Cloud_Service_OnIdentity::class,
                                    Cloud_Factory_OpenNebula::class );

        $serviceManager->setFactory(Cloud_Service_OnVmQuota::class,
                                    Cloud_Factory_OpenNebula::class );
    }
}