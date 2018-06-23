<?php

namespace Application\Entity;

class Wallets implements WalletsInterface {

    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $type;

    /**
     * @var string
     */
    public $number;

    /**
     * @var float
     */
    public $balance;

    /**
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return self
     */
    public function setId($id) {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getType() {
        return $this->type;
    }

    /**
     * @param string $type
     *
     * @return self
     */
    public function setType($type) {
        $this->type = $type;

        return $this;
    }

    /**
     * @return string
     */
    public function getNumber() {
        return $this->number;
    }

    /**
     * @param string $number
     *
     * @return self
     */
    public function setNumber($number) {
        $this->number = $number;

        return $this;
    }

    /**
     * @return float
     */
    public function getBalance() {
        return $this->balance;
    }

    /**
     * @param float $balance
     *
     * @return self
     */
    public function setBalance($balance) {
        $this->balance = $balance;

        return $this;
    }

}
