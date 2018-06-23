<?php

namespace Application\Factory\Mapper;

use Interop\Container\ContainerInterface;
use Application\Mapper\SettingsMapper;
use Zend\ServiceManager\Factory\FactoryInterface;

class SettingsMapperFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        $dbAdapter = $container->get('Zend\Db\Adapter\Adapter');
        $settingsMapper = new SettingsMapper();
        $settingsMapper->setAdapter($dbAdapter);
        $settingsMapper->setTable('settings');
        return $settingsMapper;
    }

}
