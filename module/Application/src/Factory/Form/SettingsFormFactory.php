<?php

namespace Application\Factory\Form;

use Interop\Container\ContainerInterface;
use Application\Form\SettingsForm;
use Zend\ServiceManager\Factory\FactoryInterface;

class SettingsFormFactory implements FactoryInterface {

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        $form = new SettingsForm();
        return $form;
    }

}
