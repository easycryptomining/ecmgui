<?php

namespace Application\Factory\Mapper;

use Interop\Container\ContainerInterface;
use Application\Mapper\WemosMapper;
use Zend\ServiceManager\Factory\FactoryInterface;

class WemosMapperFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        $dbAdapter = $container->get('Zend\Db\Adapter\Adapter');
        $walletsMapper = new WemosMapper();
        $walletsMapper->setAdapter($dbAdapter);
        $walletsMapper->setTable('wemos');
        return $walletsMapper;
    }

}
