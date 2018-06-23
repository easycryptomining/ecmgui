<?php

namespace Application\Mapper;

use Application\Entity\WemosInterface;

interface WemosMapperInterface {

    /**
     * Get plugins by ID
     *
     * @param int $id
     * @return PluginsInterface
     */
    public function getById($id);

    /**
     * Get all users or by filter
     *
     * @param Where|\Closure|string|array $where
     * @param array|string $order
     * @param int $limit
     * @param int $offset
     * @return UserInterface
     */
    public function getByFilter($where, $order, $limit, $offset);

    /**
     * Insert data
     *
     * @param array $data
     * @return mixed|null
     */
    public function insert($data);

    /**
     * Update data
     *
     * @param array $data
     * @param Where|string|array|\Closure $where
     * @return bool
     */
    public function update($data, $where);

    /**
     * Delete
     *
     * @param Where|\Closure|string|array $where
     * @param bool
     */
    public function delete($where);

    /**
     * Set entity prototype
     *
     * @param WemosInterface $entityPrototype
     * @return AbstractMapper
     */
    public function setEntityPrototype(WemosInterface $entityPrototype);

    /**
     * Get entity prototype
     *
     * @return WemosInterface
     */
    public function getEntityPrototype();
}
