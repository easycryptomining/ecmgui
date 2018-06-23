<?php

namespace Application\Factory\Form;

use Interop\Container\ContainerInterface;
use Application\Form\PluginsForm;
use Zend\ServiceManager\Factory\FactoryInterface;

class PluginsFormFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        $form = new PluginsForm();
        return $form;
    }

}
