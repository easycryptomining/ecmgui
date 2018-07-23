<?php

namespace Application\Service;

use Interop\Container\ContainerInterface;
use Application\Mapper\SettingsMapperInterface;
use Zend\ServiceManager\ServiceManager;

class SettingsService implements SettingsServiceInterface {

    /**
     * @var SettingsMapperInterface
     */
    protected $settingsMapper = null;

    /**
     * @var ServiceManager
     */
    protected $serviceManager = null;

    /**
     * {@inheritDoc} 
     */
    public function getSettingsById($id) {
        return $this->getSettingsMapper()->getById($id);
    }

    /**
     * {@inheritDoc} 
     */
    public function updateSettings($data, $where) {
        return $this->getSettingsMapper()->update($data, $where);
    }

    /**
     * Set Settings mapper
     *
     * @param SettingsMapperInterface $settingsMapper
     */
    public function setSettingsMapper(SettingsMapperInterface $settingsMapper) {
        $this->settingsMapper = $settingsMapper;
        return $this;
    }

    /**
     * Get Settings mapper
     * 	
     * @return SettingsMapperInterface
     */
    public function getSettingsMapper() {
        if (null === $this->settingsMapper) {
            $this->settingsMapper = $this->getServiceManager()->get('SettingsMapper');
        }
        return $this->settingsMapper;
    }

    /**
     * Set service manager
     *
     * @param ContainerInterface $serviceManager
     */
    public function setServiceManager(ContainerInterface $serviceManager) {
        $this->serviceManager = $serviceManager;
        return $this;
    }

    /**
     * Get service manager
     *
     * @return ServiceManager
     */
    public function getServiceManager() {
        return $this->serviceManager;
    }

}
