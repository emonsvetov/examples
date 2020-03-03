<?php
/**
 * Created by PhpStorm.
 * User: e.monsvetov
 * Date: 28/04/2018
 * Time: 17:40
 */

use SJX\Tool\OpenStack;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use SJX\Tool\Log;
use SJX\Tool\Log\Debug;


class Cloud_Factory_OpenStack implements FactoryInterface
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

            $config = [
                'authUrl'  => $cloud['cloudapiurl'],
                'region'   => 'RegionOne',
                'user'     => [
                    'name'     => $cloud['cloudapilogin'],
                    'password' => $cloud['cloudapipass'],
                    'domain'   => [
                        'id' => 'default'
                    ]
                ]
            ];

            if(!empty($options['projectnativeid'])){
                $config['scope'] = [
                    'project' => [
                        'id' => $options['projectnativeid']
                    ]
                ];
            }

            $openStack = new OpenStack($config, null, new Log( new Debug() ));
            return new $requestedName($openStack);

        }
    }
}