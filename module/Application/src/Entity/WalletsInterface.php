<?php

namespace Application\Entity;

interface WalletsInterface {

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
    public function getType();

    /**
     * @param string $type
     *
     * @return self
     */
    public function setType($type);

    /**
     * @return string
     */
    public function getNumber();

    /**
     * @param string $number
     *
     * @return self
     */
    public function setNumber($number);

    /**
     * @return float
     */
    public function getBalance();

    /**
     * @param float $balance
     *
     * @return self
     */
    public function setBalance($balance);

}
