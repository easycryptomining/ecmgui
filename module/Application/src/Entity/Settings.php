<?php

namespace Application\Entity;

class Settings implements SettingsInterface {

    /**
     * @var int
     */
    public $id;

    /**
     * @var varchar
     */
    public $arlousername;

    /**
     * @var varchar
     */
    public $arlopassword;

    /**
     * @var int
     */
    public $refresh;

    /**
     * @var float
     */
    public $powercost;

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
     * @return varchar
     */
    public function getArloUsername() {
        return $this->arlousername;
    }

    /**
     * @param varchar $arlousername
     *
     * @return self
     */
    public function setArloUsername($arlousername) {
        $this->arlousername = $arlousername;

        return $this;
    }

    /**
     * @return varchar
     */
    public function getArloPassword() {
        return $this->arlopassword;
    }

    /**
     * @param varchar $arlopassword
     *
     * @return self
     */
    public function setArloPassword($arlopassword) {
        $this->arlopassword = $arlopassword;

        return $this;
    }

    /**
     * @return int
     */
    public function getRefresh() {
        return $this->refresh;
    }

    /**
     * @param int $refresh
     *
     * @return self
     */
    public function setRefresh($refresh) {
        $this->refresh = $refresh;

        return $this;
    }

    /**
     * @return float
     */
    public function getPowerCost() {
        return $this->powercost;
    }

    /**
     * @param float $powercost
     *
     * @return self
     */
    public function setPowerCost($powercost) {
        $this->powercost = $powercost;

        return $this;
    }

}
