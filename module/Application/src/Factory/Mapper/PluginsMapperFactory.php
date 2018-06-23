<?php

namespace Application\Factory\Mapper;

use Interop\Container\ContainerInterface;
use Application\Mapper\PluginsMapper;
use Zend\ServiceManager\Factory\FactoryInterface;

class PluginsMapperFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        $dbAdapter = $container->get('Zend\Db\Adapter\Adapter');
        $pluginsMapper = new PluginsMapper();
        $pluginsMapper->setAdapter($dbAdapter);
        $pluginsMapper->setTable('plugins');
        return $pluginsMapper;
    }

}
