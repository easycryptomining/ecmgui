<?php

namespace Application\Mapper;

use Application\Entity\PluginsInterface;

interface PluginsMapperInterface {

    /**
     * Get plugins by ID
     *
     * @param int $id
     * @return PluginsInterface
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
     * @param PluginsInterface $entityPrototype
     * @return AbstractMapper
     */
    public function setEntityPrototype(PluginsInterface $entityPrototype);

    /**
     * Get entity prototype
     *
     * @return PluginsInterface
     */
    public function getEntityPrototype();
}
