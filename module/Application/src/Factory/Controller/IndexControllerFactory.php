<?php

namespace Application\Factory\Controller;

use Interop\Container\ContainerInterface;
use Application\Controller\IndexController;
use Zend\ServiceManager\Factory\FactoryInterface;

class IndexControllerFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        $pluginsService = $container->get('PluginsService');
        $walletsService = $container->get('WalletsService');
        $settingsService = $container->get('SettingsService');
        $workersService = $container->get('WorkersService');
        $wemosService = $container->get('WemosService');
        $indexController = new IndexController($pluginsService, $walletsService, $settingsService, $workersService, $wemosService);
        return $indexController;
    }

}
