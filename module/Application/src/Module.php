<?php

namespace Application;

use Zend\ModuleManager\Feature\ConfigProviderInterface;

class Module implements ConfigProviderInterface {

    const VERSION = '2.1';
    const APP = 'EcmGui';

    public function getConfig() {
        return include __DIR__ . '/../config/module.config.php';
    }

}
