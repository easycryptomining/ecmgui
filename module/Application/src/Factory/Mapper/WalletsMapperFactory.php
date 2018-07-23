<?php

namespace Application\Factory\Mapper;

use Interop\Container\ContainerInterface;
use Application\Mapper\WalletsMapper;
use Zend\ServiceManager\Factory\FactoryInterface;

class WalletsMapperFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        $dbAdapter = $container->get('Zend\Db\Adapter\Adapter');
        $walletsMapper = new WalletsMapper();
        $walletsMapper->setAdapter($dbAdapter);
        $walletsMapper->setTable('wallets');
        return $walletsMapper;
    }

}
