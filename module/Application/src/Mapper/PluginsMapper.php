<?php

namespace Application\Mapper;

use Application\Entity\Plugins;
use Application\Entity\PluginsInterface;

class PluginsMapper extends AbstractMapper implements PluginsMapperInterface {

    /**
     * {@inheritDoc} 
     */
    public function getById($id) {
        $select = $this->getSelect();
        $select->where(['id' => (int) $id]);
        $entity = parent::select($select)->current();
        return $entity;
    }

    /**
     * {@inheritDoc} 
     */
    public function update($data, $where) {
        $result = parent::update($data, $where);
        return $result->getAffectedRows() > 0 ? true : false;
    }
    
    /**
     * Set entity prototype
     *
     * @param PluginsInterface $entityPrototype
     * @return AbstractMapper
     */
    public function setEntityPrototype(PluginsInterface $entityPrototype) {
        $this->entityPrototype = $entityPrototype;
        return $this;
    }

    /**
     * Get entity prototype
     *
     * @return PluginsInterface
     */
    public function getEntityPrototype() {
        if (!$this->entityPrototype instanceof PluginsInterface) {
            $this->entityPrototype = new Plugins();
        }
        return $this->entityPrototype;
    }

}
