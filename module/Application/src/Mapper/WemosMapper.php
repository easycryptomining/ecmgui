<?php

namespace Application\Mapper;

use Application\Entity\Wemos;
use Application\Entity\WemosInterface;

class WemosMapper extends AbstractMapper implements WemosMapperInterface {

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
    public function getByFilter($where, $order, $limit, $offset) {
        $select = $this->getSelect();
        if (null !== $where) {
            $select->where($where);
        }
        if (null !== $order) {
            $select->order($order);
        }
        if (null !== $limit) {
            $select->limit($limit);
        }
        if (null !== $offset) {
            $select->offset($offset);
        }

        $entities = parent::select($select);
        return $entities;
    }

    /**
     * {@inheritDoc} 
     */
    public function insert($data) {
        $result = parent::insert($data);
        return $result->getGeneratedValue();
    }

    /**
     * {@inheritDoc} 
     */
    public function update($data, $where) {
        $result = parent::update($data, $where);
        return $result->getAffectedRows() > 0 ? true : false;
    }

    /**
     * {@inheritDoc} 
     */
    public function delete($where) {
        $result = parent::delete($where);
        return $result->getAffectedRows() > 0 ? true : false;
    }

    /**
     * Set entity prototype
     *
     * @param WemosInterface $entityPrototype
     * @return AbstractMapper
     */
    public function setEntityPrototype(WemosInterface $entityPrototype) {
        $this->entityPrototype = $entityPrototype;
        return $this;
    }

    /**
     * Get entity prototype
     *
     * @return WemosInterface
     */
    public function getEntityPrototype() {
        if (!$this->entityPrototype instanceof WemosInterface) {
            $this->entityPrototype = new Wemos();
        }
        return $this->entityPrototype;
    }

}
