<?php

namespace Application\Entity;

interface SettingsInterface {

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
     * @return varchar
     */
    public function getArloUsername();

    /**
     * @param varchar $arlousername
     *
     * @return self
     */
    public function setArloUsername($arlousername);

    /**
     * @return varchar
     */
    public function getArloPassword();

    /**
     * @param varchar $arlopassword
     *
     * @return self
     */
    public function setArloPassword($arlopassword);

    /**
     * @return int
     */
    public function getRefresh();

    /**
     * @param int $refresh
     *
     * @return self
     */
    public function setRefresh($refresh);

    /**
     * @return float
     */
    public function getPowerCost();

    /**
     * @param float $powercost
     *
     * @return self
     */
    public function setPowerCost($powercost);

}
