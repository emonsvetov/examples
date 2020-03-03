<?php
/**
 * Created by PhpStorm.
 * User: e.monsvetov
 * Date: 05/04/2018
 * Time: 16:01
 */

use SJX\Db\Adapter\ClickHouse;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class Cloud_Factory_ClickHouse implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null )
    {
        if( !SJX_Instance::isDbRegistered(ClickHouse::ADAPTER_NAME) ){

            $cloud = null;
            if(empty($options['cloudid']) && !empty($options['projectid'])){
                $cloudProjectsService = new Cloud_Service_CloudProjects();
                $project = $cloudProjectsService->getById($options['projectid']);
                $options['cloudid'] = $project['cloudid'];
            }

            if(!empty($options['cloudid'])){
                $cloudService = $container->get(Cloud_Service_Clouds::class);
                $cloud = $cloudService->getById( $options['cloudid'] );
            }

            $clickHouseAdapter = new ClickHouse(
                rtrim($cloud['cloudstaturl'], "/"),
                $cloud['cloudstatlogin'],
                $cloud['cloudstatpass'],
                8123
            );

            SJX_Instance::setDb(new SJX_Db_Adapter( $clickHouseAdapter ));
        }

        return new $requestedName();
    }
}