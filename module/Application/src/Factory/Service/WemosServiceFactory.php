<?php

namespace Application\Factory\Service;

use Interop\Container\ContainerInterface;
use Application\Service\WemosService;
use Zend\ServiceManager\Factory\FactoryInterface;

class WemosServiceFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        $wemosService = new WemosService();
        $wemosService->setServiceManager($container);
        return $wemosService;
    }

}
