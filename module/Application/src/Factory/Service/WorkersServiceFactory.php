<?php

namespace Application\Factory\Service;

use Interop\Container\ContainerInterface;
use Application\Service\WorkersService;
use Zend\ServiceManager\Factory\FactoryInterface;

class WorkersServiceFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        $workersService = new WorkersService();
        $workersService->setServiceManager($container);
        return $workersService;
    }

}
