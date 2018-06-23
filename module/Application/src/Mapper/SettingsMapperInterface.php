<?php

namespace Application\Mapper;

use Application\Entity\SettingsInterface;

interface SettingsMapperInterface {

    /**
     * Get settings by ID
     *
     * @param int $id
     * @return SettingsInterface
     */
    public function getById($id);

    /**
     * Update data
     *
     * @param array $data
     * @param Where|string|array|\Closure $where
     * @return bool
     */
    public function update($data, $where);
    
    /**
     * Set entity prototype
     *
     * @param SettingsInterface $entityPrototype
     * @return AbstractMapper
     */
    public function setEntityPrototype(SettingsInterface $entityPrototype);

    /**
     * Get entity prototype
     *
     * @return SettingsInterface
     */
    public function getEntityPrototype();
}
