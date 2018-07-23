<?php

namespace Application\Factory\Service;

use Interop\Container\ContainerInterface;
use Application\Service\SettingsService;
use Zend\ServiceManager\Factory\FactoryInterface;

class SettingsServiceFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        $settingsService = new SettingsService();
        $settingsService->setServiceManager($container);
        return $settingsService;
    }

}
