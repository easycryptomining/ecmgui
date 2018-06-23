<?php

namespace Application\Entity;

interface WorkersInterface {


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
    public function getIp();

    /**
     * @param string $ip
     *
     * @return self
     */
    public function setIp($ip);
    
    /**
     * @return string
     */
    public function getPool();

    /**
     * @param string $pool
     *
     * @return self
     */
    public function setPool($pool);
    
    /**
     * @return string
     */
    public function getInsight();

    /**
     * @param string $insight
     *
     * @return self
     */
    public function setInsight($insight);
    
    /**
     * @return string
     */
    public function getSoftware();

    /**
     * @param string $software
     *
     * @return self
     */
    public function setSoftware($software);
    
    /**
     * @return integer
     */
    public function getSoftwareport();

    /**
     * @param integer $softwareport
     *
     * @return self
     */
    public function setSoftwareport($softwareport);
    
    /**
     * @return float
     */
    public function getAmp();

    /**
     * @param float $amp
     *
     * @return self
     */
    public function setAmp($amp);
    
    /**
     * @return integer
     */
    public function getWalletid();

    /**
     * @param integer $walletid
     *
     * @return self
     */
    public function setWalletid($walletid);
    
    /**
     * @return string
     */
    public function getSshuser();

    /**
     * @param string $sshuser
     *
     * @return self
     */
    public function setSshuser($sshuser);
    
    /**
     * @return string
     */
    public function getSshpassword();

    /**
     * @param string $sshpassword
     *
     * @return self
     */
    public function setSshpassword($sshpassword);
    
    /**
     * @return integer
     */
    public function getSshport();

    /**
     * @param integer $sshport
     *
     * @return self
     */
    public function setSshport($sshport);
    
    /**
     * @return bool
     */
    public function ping();

}
