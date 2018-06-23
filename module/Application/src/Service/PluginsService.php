<?php

namespace Application\Service;

use Interop\Container\ContainerInterface;
use Application\Mapper\PluginsMapperInterface;
use Zend\ServiceManager\ServiceManager;

class PluginsService implements PluginsServiceInterface {

    /**
     * @var PluginsMapperInterface
     */
    protected $pluginsMapper = null;

    /**
     * @var ServiceManager
     */
    protected $serviceManager = null;

    /**
     * {@inheritDoc} 
     */
    public function getPluginsById($id) {
        return $this->getPluginsMapper()->getById($id);
    }

    /**
     * {@inheritDoc} 
     */
    public function updatePlugins($data, $where) {
        return $this->getPluginsMapper()->update($data, $where);
    }

    /**
     * Set Plugins mapper
     *
     * @param PluginsMapperInterface $pluginsMapper
     */
    public function setPluginsMapper(PluginsMapperInterface $pluginsMapper) {
        $this->userMapper = $userMapper;
        return $this;
    }

    /**
     * Get Plugins mapper
     * 	
     * @return PluginsMapperInterface
     */
    public function getPluginsMapper() {
        if (null === $this->pluginsMapper) {
            $this->pluginsMapper = $this->getServiceManager()->get('PluginsMapper');
        }
        return $this->pluginsMapper;
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
