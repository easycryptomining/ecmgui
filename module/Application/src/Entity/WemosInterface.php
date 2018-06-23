<?php

namespace Application\Entity;

interface WemosInterface {

    /**
     * @return int
     */
    public function getId();

    /**
     * @param int $id
     *
     * @return self
     */
    public function setId($id);
    
    /**
     * @return string
     */
    public function getName();

    /**
     * @param string $name
     *
     * @return self
     */
    public function setName($name);

    /**
     * @return string
     */
    public function getType();

    /**
     * @param string $type
     *
     * @return self
     */
    public function setType($type);
    
    /**
     * @return bool
     */
    public function ping();

}
