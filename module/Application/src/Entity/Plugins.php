<?php

namespace Application\Entity;

class Plugins implements PluginsInterface {

    /**
     * @var int
     */
    public $id;

    /**
     * @var bool
     */
    public $crypto;

    /**
     * @var bool
     */
    public $arlo;

    /**
     * @var bool
     */
    public $wemo;

    /**
     * @var bool
     */
    public $hue;

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
     * @return bool
     */
    public function getCrypto() {
        return $this->crypto;
    }

    /**
     * @param bool $crypto
     *
     * @return self
     */
    public function setCrypto($crypto) {
        $this->crypto = $crypto;

        return $this;
    }

    /**
     * @return bool
     */
    public function getArlo() {
        return $this->arlo;
    }

    /**
     * @param bool $arlo
     *
     * @return self
     */
    public function setArlo($arlo) {
        $this->arlo = $arlo;

        return $this;
    }

    /**
     * @return bool
     */
    public function getWemo() {
        return $this->wemo;
    }

    /**
     * @param bool $wemo
     *
     * @return self
     */
    public function setWemo($wemo) {
        $this->wemo = $wemo;

        return $this;
    }

    /**
     * @return bool
     */
    public function getHue() {
        return $this->hue;
    }

    /**
     * @param bool $hue
     *
     * @return self
     */
    public function setHue($hue) {
        $this->hue = $hue;

        return $this;
    }

}
