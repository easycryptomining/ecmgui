<?php

namespace Application\Entity;

class Workers implements WorkersInterface {

    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $ip;

    /**
     * @var string
     */
    public $pool;

    /**
     * @var string
     */
    public $insight;

    /**
     * @var string
     */
    public $software;

    /**
     * @var integer
     */
    public $softwareport;

    /**
     * @var float
     */
    public $amp;

    /**
     * @var integer
     */
    public $walletid;
    
    /**
     * @var string
     */
    public $sshuser;
    
    /**
     * @var string
     */
    public $sshpassword;
    
    /**
     * @var integer
     */
    public $sshport;

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
    public function getName() {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return self
     */
    public function setName($name) {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getIp() {
        return $this->ip;
    }

    /**
     * @param string $ip
     *
     * @return self
     */
    public function setIp($ip) {
        $this->ip = $ip;

        return $this;
    }

    /**
     * @return string
     */
    public function getPool() {
        return $this->pool;
    }

    /**
     * @param string $pool
     *
     * @return self
     */
    public function setPool($pool) {
        $this->pool = $pool;

        return $this;
    }

    /**
     * @return string
     */
    public function getInsight() {
        return $this->insight;
    }

    /**
     * @param string $insight
     *
     * @return self
     */
    public function setInsight($insight) {
        $this->insight = $insight;

        return $this;
    }

    /**
     * @return string
     */
    public function getSoftware() {
        return $this->software;
    }

    /**
     * @param string $software
     *
     * @return self
     */
    public function setSoftware($software) {
        $this->software = $software;

        return $this;
    }

    /**
     * @return integer
     */
    public function getSoftwareport() {
        return $this->softwareport;
    }

    /**
     * @param integer $softwareport
     *
     * @return self
     */
    public function setSoftwareport($softwareport) {
        $this->softwareport = $softwareport;

        return $this;
    }

    /**
     * @return float
     */
    public function getAmp() {
        return $this->amp;
    }

    /**
     * @param float $amp
     *
     * @return self
     */
    public function setAmp($amp) {
        $this->amp = $amp;

        return $this;
    }

    /**
     * @return integer
     */
    public function getWalletid() {
        return $this->walletid;
    }

    /**
     * @param integer $walletid
     *
     * @return self
     */
    public function setWalletid($walletid) {
        $this->walletid = $walletid;

        return $this;
    }
    
    /**
     * @return string
     */
    public function getSshuser() {
        return $this->sshuser;
    }

    /**
     * @param string $sshuser
     *
     * @return self
     */
    public function setSshuser($sshuser) {
        $this->sshuser = $sshuser;

        return $this;
    }
    
    /**
     * @return string
     */
    public function getSshpassword() {
        return $this->sshpassword;
    }

    /**
     * @param string $sshpassword
     *
     * @return self
     */
    public function setSshpassword($sshpassword) {
        $this->sshpassword = $sshpassword;

        return $this;
    }
    
    /**
     * @return integer
     */
    public function getSshport() {
        return $this->sshport;
    }

    /**
     * @param integer $sshport
     *
     * @return self
     */
    public function setSshport($sshport) {
        $this->sshport = $sshport;

        return $this;
    }

    /**
     * @return bool
     */
    public function ping() {
        $result = false;
        if (!empty($this->name)) {
            $pingresult = exec("ping -c 1 -s 64 $this->name", $outcome, $status);
            if (0 == $status) {
                $result = true;
            } else {
                if (!empty($this->ip)) {
                    $pingresult = exec("ping -c 1 -s 64 $this->ip", $outcome, $status);
                    if (0 == $status) {
                        $result = true;
                    }
                }
            }
        } else {
            if (!empty($this->ip)) {
                $pingresult = exec("ping -c 1 -s 64 $this->ip", $outcome, $status);
                if (0 == $status) {
                    $result = true;
                }
            }
        }
        return $result;
    }

}
