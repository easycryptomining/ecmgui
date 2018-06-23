<?php

namespace Application\Mapper;

use Application\Entity\Settings;
use Application\Entity\SettingsInterface;

class SettingsMapper extends AbstractMapper implements SettingsMapperInterface {

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
     * @param SettingsInterface $entityPrototype
     * @return AbstractMapper
     */
    public function setEntityPrototype(SettingsInterface $entityPrototype) {
        $this->entityPrototype = $entityPrototype;
        return $this;
    }

    /**
     * Get entity prototype
     *
     * @return SettingsInterface
     */
    public function getEntityPrototype() {
        if (!$this->entityPrototype instanceof SettingsInterface) {
            $this->entityPrototype = new Settings();
        }
        return $this->entityPrototype;
    }

}
