<?php

namespace Application\Entity;

interface PluginsInterface {

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
     * @return bool
     */
    public function getCrypto();

    /**
     * @param bool $crypto
     *
     * @return self
     */
    public function setCrypto($crypto);

    /**
     * @return bool
     */
    public function getArlo();

    /**
     * @param bool $arlo
     *
     * @return self
     */
    public function setArlo($arlo);

    /**
     * @return bool
     */
    public function getWemo();

    /**
     * @param bool $wemo
     *
     * @return self
     */
    public function setWemo($wemo);

    /**
     * @return bool
     */
    public function getHue();

    /**
     * @param bool $hue
     *
     * @return self
     */
    public function setHue($hue);
}
