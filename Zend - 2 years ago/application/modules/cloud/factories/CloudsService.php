<?php
/**
 * Created by PhpStorm.
 * User: e.monsvetov
 * Date: 05/04/2018
 * Time: 16:17
 */

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class Cloud_Factory_CloudsService implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null )
    {
        if(SJX_Instance::isClient()){
            $cloudModel = new Private_Model_vcClouds();
        }elseif(SJX_Instance::isOffice()){
            $cloudModel = new Cloud_Model_vaClouds();
        }

        return new Cloud_Service_Clouds($cloudModel);
    }
}