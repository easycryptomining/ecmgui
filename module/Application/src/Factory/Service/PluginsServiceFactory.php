<?php

namespace Application\Factory\Service;

use Interop\Container\ContainerInterface;
use Application\Service\PluginsService;
use Zend\ServiceManager\Factory\FactoryInterface;

class PluginsServiceFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        $pluginsService = new PluginsService();
        $pluginsService->setServiceManager($container);
        return $pluginsService;
    }

}
