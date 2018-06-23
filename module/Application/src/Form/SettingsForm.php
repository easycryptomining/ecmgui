<?php

namespace Application\Form;

use Zend\Form\Form;
use Zend\Form\Element;

class SettingsForm extends Form {

    /**
     * Constructor.     
     */
    public function __construct() {
        // Define form name
        parent::__construct('settings-form');

        $this->setAttribute('method', 'post');
        $this->setAttribute('action', '/settings/update-settings');

        $this->addElements();
    }

    /**
     * Add elements to form
     */
    protected function addElements() {
        $this->add([
            'type' => Element\Text::class,
            'name' => 'arlousername',
            'attributes' => [
                'id' => 'arlousername',
                'autocomplete' => 'off',
                'class' => 'form-control'
            ],
            'options' => [
                'label' => 'Arlo username',
            ],
        ]);
        $this->add([
            'type' => Element\Text::class,
            'name' => 'arlopassword',
            'attributes' => [
                'id' => 'arlopassword',
                'autocomplete' => 'off',
                'class' => 'form-control'
            ],
            'options' => [
                'label' => 'Arlo password',
            ],
        ]);
        $this->add([
            'type' => Element\Text::class,
            'name' => 'refresh',
            'attributes' => [
                'id' => 'refresh',
                'autocomplete' => 'off',
                'class' => 'form-control'
            ],
            'options' => [
                'label' => 'Refresh rate (0 = OFF)',
            ],
        ]);
        $this->add([
            'type' => Element\Text::class,
            'name' => 'powercost',
            'attributes' => [
                'id' => 'powercost',
                'autocomplete' => 'off',
                'class' => 'form-control'
            ],
            'options' => [
                'label' => 'Power cost',
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
