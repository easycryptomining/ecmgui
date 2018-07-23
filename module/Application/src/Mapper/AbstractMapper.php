<?php

namespace Application\Mapper;

use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Where;
use Zend\Hydrator\HydratorInterface;
use Zend\Hydrator\ClassMethods;

abstract class AbstractMapper {

    /**
     * @var boolean
     */
    protected $isInitialized = false;

    /**
     * @var AdapterInterface
     */
    protected $adapter = null;

    /**
     * @var string|array|TableIdentifier
     */
    protected $table = null;

    /**
     * @var Sql
     */
    protected $sql = null;

    /**
     * @var HydratorInterface
     */
    protected $hydrator = null;

    /**
     * @var PluginsInterface
     */
    protected $entityPrototype = null;

    /**
     * Checks initialization setup
     *
     * @throws \Exception
     * @return null
     */
    public function initialize() {
        if ($this->isInitialized) {
            return;
        }

        if (!$this->adapter instanceof AdapterInterface) {
            throw new \Exception("This table does not have an Adapter setup!");
        }

        if (!is_string($this->table) && !$this->table instanceof TableIdentifier && !is_array($this->table)) {
            throw new \Exception('This table object does not have a valid table set!');
        }

        $this->isInitialized = true;
    }

    /**
     * Select
     *
     * @param Select $select
     * @return ResultSet|array
     */
    protected function select(Select $select) {
        if (!$this->isInitialized) {
            $this->initialize();
        }

        $sql = $this->getSql();
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        if ($result instanceof ResultInterface && $result->isQueryResult()) {
            $resultSet = new HydratingResultSet($this->getHydrator(), $this->getEntityPrototype());
            $resultSet->initialize($result);
            return $resultSet;
        }

        return array();
    }

    /**
     * Insert
     *
     * @param array $data
     * @return ResultInterface
     */
    protected function insert($data) {
        if (!$this->isInitialized) {
            $this->initialize();
        }

        $sql = $this->getSql();
        $insert = $sql->insert();
        $insert->values($data);
        $statement = $sql->prepareStatementForSqlObject($insert);
        $result = $statement->execute();
        return $result;
    }

    /**
     * Update
     *
     * @param array $data
     * @param Where|string|array|\Closure $where
     * @return ResultInterface
     */
    protected function update($data, $where) {
        if (!$this->isInitialized) {
            $this->initialize();
        }

        $sql = $this->getSql();
        $update = $sql->update();
        $update->set($data);
        $update->where($where);
        $statement = $sql->prepareStatementForSqlObject($update);
        $result = $statement->execute();
        return $result;
    }

    /**
     * Delete
     *
     * @param Where|\Closure|string|array $where
     * @param ResultInterface
     */
    protected function delete($where) {
        if (!$this->isInitialized) {
            $this->initialize();
        }

        $sql = $this->getSql();
        $delete = $sql->delete();
        $delete->where($where);
        $statement = $sql->prepareStatementForSqlObject($delete);
        $result = $statement->execute();

        return $result;
    }

    /**
     * Gets an instance of Select
     * 
     * @return Select
     */
    public function getSelect() {
        return $this->getSql()->select();
    }

    /**
     * Set DB adapter 
     *
     * @param AdapterInterface $adapter
     * @return AbstractMapper
     */
    public function setAdapter(AdapterInterface $adapter) {
        $this->adapter = $adapter;
        return $this;
    }

    /**
     * Get DB adapter
     *
     * @return AdapterInterface
     */
    public function getAdapter() {
        return $this->adapter;
    }

    /**
     * Set table name
     *
     * @param string $table
     * @return AbstractMapper
     */
    public function setTable($table) {
        $this->table = $table;
        return $this;
    }

    /**
     * Get table name
     *
     * @return string
     */
    public function getTable() {
        return $this->table;
    }

    /**
     * Set Sql
     *
     * @param Sql $sql
     * @return AbstractMapper
     */
    public function setSql(Sql $sql) {
        $this->sql = $sql;
        return $this;
    }

    /**
     * Get Sql
     *
     * @return Sql
     */
    public function getSql() {
        if (!$this->sql instanceof Sql) {
            $this->sql = new Sql($this->getAdapter(), $this->getTable());
        }
        return $this->sql;
    }

    /**
     * Set ClassMethods
     *
     * @param HydratorInterface $hydrator
     * @return AbstractMapper
     */
    public function setHydrator(HydratorInterface $hydrator) {
        $this->hydrator = $hydrator;
        return $this;
    }

    /**
     * Get ClassMethods
     *
     * @return HydratorInterface
     */
    public function getHydrator() {
        if (!$this->hydrator instanceof HydratorInterface) {
            $this->hydrator = new ClassMethods(false);
        }
        return $this->hydrator;
    }

}
