<?php

namespace Application\Factory\Controller;

use Interop\Container\ContainerInterface;
use Application\Controller\SettingsController;
use Zend\ServiceManager\Factory\FactoryInterface;

class SettingsControllerFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        $pluginsService = $container->get('PluginsService');
        $pluginsForm = $container->get('PluginsForm');
        $walletsService = $container->get('WalletsService');
        $settingsService = $container->get('SettingsService');
        $settingsForm = $container->get('SettingsForm');
        $workersService = $container->get('WorkersService');
        $settingsController = new SettingsController($pluginsService, $pluginsForm, $walletsService, $settingsService, $settingsForm, $workersService);
        return $settingsController;
    }

}
