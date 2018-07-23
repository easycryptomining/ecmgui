<?php

namespace Application\Service;

use Interop\Container\ContainerInterface;
use Application\Mapper\WalletsMapperInterface;
use Zend\ServiceManager\ServiceManager;

class WalletsService implements WalletsServiceInterface {

    /**
     * @var WalletsMapperInterface
     */
    protected $walletsMapper = null;

    /**
     * @var ServiceManager
     */
    protected $serviceManager = null;

    /**
     * {@inheritDoc} 
     */
    public function getWalletsById($id) {
        return $this->getWalletsMapper()->getById($id);
    }

    /**
     * {@inheritDoc} 
     */
    public function getWalletsByFilter($where = null, $order = null, $limit = null, $offset = null) {
        return $this->getWalletsMapper()->getByFilter($where, $order, $limit, $offset);
    }

    /**
     * {@inheritDoc} 
     */
    public function insertWallets($data) {
        return $this->getWalletsMapper()->insert($data);
    }

    /**
     * {@inheritDoc} 
     */
    public function updateWallets($data, $where) {
        return $this->getWalletsMapper()->update($data, $where);
    }

    /**
     * {@inheritDoc} 
     */
    public function deleteWallets($where) {
        return $this->getWalletsMapper()->delete($where);
    }

    /**
     * Set Wallets mapper
     *
     * @param WalletsMapperInterface $walletsMapper
     */
    public function setWalletsMapper(WalletsMapperInterface $walletsMapper) {
        $this->walletsMapper = $walletsMapper;
        return $this;
    }

    /**
     * Get Wallets mapper
     * 	
     * @return WalletsMapperInterface
     */
    public function getWalletsMapper() {
        if (null === $this->walletsMapper) {
            $this->walletsMapper = $this->getServiceManager()->get('WalletsMapper');
        }
        return $this->walletsMapper;
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
