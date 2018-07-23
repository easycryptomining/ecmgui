<?php

namespace Application\Service;

use Interop\Container\ContainerInterface;
use Application\Mapper\WemosMapperInterface;
use Zend\ServiceManager\ServiceManager;

class WemosService implements WemosServiceInterface {

    /**
     * @var WemosMapperInterface
     */
    protected $wemosMapper = null;

    /**
     * @var ServiceManager
     */
    protected $serviceManager = null;

    /**
     * {@inheritDoc} 
     */
    public function getWemosById($id) {
        return $this->getWemosMapper()->getById($id);
    }

    /**
     * {@inheritDoc} 
     */
    public function getWemosByFilter($where = null, $order = null, $limit = null, $offset = null) {
        return $this->getWemosMapper()->getByFilter($where, $order, $limit, $offset);
    }

    /**
     * {@inheritDoc} 
     */
    public function insertWemos($data) {
        return $this->getWemosMapper()->insert($data);
    }

    /**
     * {@inheritDoc} 
     */
    public function updateWemos($data, $where) {
        return $this->getWemosMapper()->update($data, $where);
    }

    /**
     * {@inheritDoc} 
     */
    public function deleteWemos($where) {
        return $this->getWemosMapper()->delete($where);
    }

    /**
     * Set Wemos mapper
     *
     * @param WemosMapperInterface $wemosMapper
     */
    public function setWemosMapper(WemosMapperInterface $wemosMapper) {
        $this->wemosMapper = $wemosMapper;
        return $this;
    }

    /**
     * Get Wemos mapper
     * 	
     * @return WemosMapperInterface
     */
    public function getWemosMapper() {
        if (null === $this->wemosMapper) {
            $this->wemosMapper = $this->getServiceManager()->get('WemosMapper');
        }
        return $this->wemosMapper;
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
