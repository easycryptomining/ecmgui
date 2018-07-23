<?php

namespace Application\Factory\Service;

use Interop\Container\ContainerInterface;
use Application\Service\WalletsService;
use Zend\ServiceManager\Factory\FactoryInterface;

class WalletsServiceFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        $walletsService = new WalletsService();
        $walletsService->setServiceManager($container);
        return $walletsService;
    }

}
