<?php

namespace Application\Form;

use Zend\Form\Form;

class PluginsForm extends Form {

    /**
     * Constructor.     
     */
    public function __construct() {
        // Define form name
        parent::__construct('plugins-form');

        $this->setAttribute('method', 'post');
        $this->setAttribute('action', '/settings/update-plugins');

        $this->addElements();
    }

    /**
     * Add elements to form
     */
    protected function addElements() {
        $this->add([
            'type' => 'checkbox',
            'name' => 'arlo',
            'attributes' => [
                'id' => 'arlo',
                'autocomplete' => 'off',
                'class' => 'toggle-btn',
                'data-toggle' => 'toggle',
                'data-size' => 'mini'
            ],
            'options' => [
                'label' => 'Arlo',
            ],
        ]);
        $this->add([
            'type' => 'checkbox',
            'name' => 'wemo',
            'attributes' => [
                'id' => 'wemo',
                'autocomplete' => 'off',
                'class' => 'toggle-btn',
                'data-toggle' => 'toggle',
                'data-size' => 'mini'
            ],
            'options' => [
                'label' => 'Wemo',
            ],
        ]);
        $this->add([
            'type' => 'checkbox',
            'name' => 'hue',
            'attributes' => [
                'id' => 'hue',
                'autocomplete' => 'off',
                'class' => 'toggle-btn',
                'data-toggle' => 'toggle',
                'data-size' => 'mini'
            ],
            'options' => [
                'label' => 'Hue',
            ],
        ]);
        $this->add([
            'type' => 'checkbox',
            'name' => 'crypto',
            'attributes' => [
                'id' => 'crypto',
                'autocomplete' => 'off',
                'class' => 'toggle-btn',
                'data-toggle' => 'toggle',
                'data-size' => 'mini'
            ],
            'options' => [
                'label' => 'Crypto',
            ],
        ]);

        // Add the CSRF field
        $this->add([
            'type' => 'csrf',
            'name' => 'csrf',
            'attributes' => [],
            'options' => [
                'csrf_options' => [
                    'timeout' => 600
                ]
            ],
        ]);

        // Add the submit button
        $this->add([
            'type' => 'submit',
            'name' => 'submit',
            'attributes' => [
                'value' => 'Save',
                'id' => 'saveButton',
                'class' => 'btn btn-success btn-xs'
            ],
        ]);
    }

}
