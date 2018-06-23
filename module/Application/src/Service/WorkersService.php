<?php

namespace Application\Service;

use Interop\Container\ContainerInterface;
use Application\Mapper\WorkersMapperInterface;
use Zend\ServiceManager\ServiceManager;

class WorkersService implements WorkersServiceInterface {

    /**
     * @var WorkersMapperInterface
     */
    protected $workersMapper = null;

    /**
     * @var ServiceManager
     */
    protected $serviceManager = null;

    /**
     * {@inheritDoc} 
     */
    public function getWorkersById($id) {
        return $this->getWorkersMapper()->getById($id);
    }

    /**
     * {@inheritDoc} 
     */
    public function getWorkersByFilter($where = null, $order = null, $limit = null, $offset = null) {
        return $this->getWorkersMapper()->getByFilter($where, $order, $limit, $offset);
    }

    /**
     * {@inheritDoc} 
     */
    public function insertWorkers($data) {
        return $this->getWorkersMapper()->insert($data);
    }

    /**
     * {@inheritDoc} 
     */
    public function updateWorkers($data, $where) {
        return $this->getWorkersMapper()->update($data, $where);
    }

    /**
     * {@inheritDoc} 
     */
    public function deleteWorkers($where) {
        return $this->getWorkersMapper()->delete($where);
    }

    /**
     * Set Workers mapper
     *
     * @param WorkersMapperInterface $workersMapper
     */
    public function setWorkersMapper(WorkersMapperInterface $workersMapper) {
        $this->workersMapper = $workersMapper;
        return $this;
    }

    /**
     * Get Workers mapper
     * 	
     * @return WorkersMapperInterface
     */
    public function getWorkersMapper() {
        if (null === $this->workersMapper) {
            $this->workersMapper = $this->getServiceManager()->get('WorkersMapper');
        }
        return $this->workersMapper;
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
