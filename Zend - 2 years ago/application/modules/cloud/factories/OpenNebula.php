<?php

use SJX\Tool\OpenNebula;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use SJX\Tool\Log;
use SJX\Tool\Log\Debug;


class Cloud_Factory_OpenNebula implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null )
    {

        if(!empty($options['projectid'])){
            $cloudProjectsService = new Cloud_Service_CloudProjects();
            $project = $cloudProjectsService->getById( $options['projectid'] );
            $options['cloudid'] = $project['cloudid'];
            $options['projectnativeid'] = $project['projectnativeid'];
        }

        if(!empty($options['cloudid'])){
            $cloudService = $container->get(Cloud_Service_Clouds::class);
            $cloud = $cloudService->getById( $options['cloudid'] );

            $options = [
                'user'      => $cloud['cloudapilogin'],
                'password'  => $cloud['cloudapipass'],
                'address'   => $cloud['cloudapiurl'],
                'useSSL'    => true,
                'port'      => $cloud['cloudapiport'],
                'path'      => 'RPC2'
            ];

            $openNebula = new OpenNebula($options, new Log( new Debug() ));
            return new $requestedName($openNebula);

        }

    }
}