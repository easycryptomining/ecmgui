<?php

namespace Application\Factory\Mapper;

use Interop\Container\ContainerInterface;
use Application\Mapper\WorkersMapper;
use Zend\ServiceManager\Factory\FactoryInterface;

class WorkersMapperFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        $dbAdapter = $container->get('Zend\Db\Adapter\Adapter');
        $workersMapper = new WorkersMapper();
        $workersMapper->setAdapter($dbAdapter);
        $workersMapper->setTable('workers');
        return $workersMapper;
    }

}
