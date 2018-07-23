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
                $result = $this->name;
            } else {
                if (!empty($this->ip)) {
                    $pingresult = exec("ping -c 1 -s 64 $this->ip", $outcome, $status);
                    if (0 == $status) {
                        $result = $this->ip;
                    }
                }
            }
        } else {
            if (!empty($this->ip)) {
                $pingresult = exec("ping -c 1 -s 64 $this->ip", $outcome, $status);
                if (0 == $status) {
                    $result = $this->ip;
                }
            }
        }
        return $result;
    }
    
    /**
     * @return string
     */
    public function temp() {
        $temp = '-';
        $workerName = $this->ping();
        if ($workerName === false) {
            $temp = 'Worker must have a name or an ip';
            return $temp;
        }

        $connection = ssh2_connect($workerName, $this->sshport);
        if (ssh2_auth_password($connection, $this->sshuser, $this->sshpassword)) {
            $stream = ssh2_exec($connection, "sensors");
            stream_set_blocking($stream, true);
            $stream_out = ssh2_fetch_stream($stream, SSH2_STREAM_STDIO);
            $sensors = stream_get_contents($stream_out);

            if (trim($sensors) !== "") {
                $sensors = str_replace(":\n", ":", $sensors);
                $sensors = str_replace("\n\n", "\n", $sensors);
                $lines = preg_split("/\n/", $sensors, -1, PREG_SPLIT_NO_EMPTY);
            }
            $matcheKey = array_keys(preg_grep('/^coretemp/i', $lines));
            $strinToParse = $lines[$matcheKey[0] + 2];
            $data = explode(" ", $strinToParse);
            $coreTemp = str_replace('+', '', $data[4]);
            $temp = "Cpu: $coreTemp °C";

            $matcheKey = array_keys(preg_grep('/^amdgpu/i', $lines));
            if (!empty($matcheKey)) {
                $i = 1;
                foreach ($matcheKey as $key) {
                    $strinToParse = $lines[$key + 3];
                    $data = explode(" ", $strinToParse);
                    $gpuTemp = str_replace('+', '', $data[8]);
                    $temp .= "<br />Gpu$i: $gpuTemp °C";
                    $i++;
                }
            } else {
                $stream = ssh2_exec($connection, "nvidia-smi --query-gpu=temperature.gpu --format=csv,noheader");
                stream_set_blocking($stream, true);
                $stream_out = ssh2_fetch_stream($stream, SSH2_STREAM_STDIO);
                $sensors = trim(stream_get_contents($stream_out));
                $data = explode(PHP_EOL, $sensors);

                $i = 1;
                foreach ($data as $value) {
                    $temp .= "<br />Gpu$i: $value °C";
                    $i++;
                }
            }
        }
        return $temp;
    }
    
    /**
     * @return string
     */
    public function uptime() {
        $uptime = '-';
        $workerName = $this->ping();
        if ($workerName === false) {
            $temp = 'Worker must have a name or an ip';
            return $temp;
        }

        $connection = ssh2_connect($workerName, $this->sshport);
        if (ssh2_auth_password($connection, $this->sshuser, $this->sshpassword)) {
            $stream = ssh2_exec($connection, "uptime -p");
            stream_set_blocking($stream, true);
            $stream_out = ssh2_fetch_stream($stream, SSH2_STREAM_STDIO);
            $uptime = stream_get_contents($stream_out);
            $uptime = str_replace('up ', '', $uptime);
            $uptime = str_replace('years', 'Y', $uptime);
            $uptime = str_replace('year', 'Y', $uptime);
            $uptime = str_replace('mounths', 'M', $uptime);
            $uptime = str_replace('mounth', 'M', $uptime);
            $uptime = str_replace('weeks', 'W', $uptime);
            $uptime = str_replace('week', 'W', $uptime);
            $uptime = str_replace('days', 'd', $uptime);
            $uptime = str_replace('day', 'd', $uptime);
            $uptime = str_replace('hours', 'h', $uptime);
            $uptime = str_replace('hour', 'h', $uptime);
            $uptime = str_replace('minutes', 'm', $uptime);
            $uptime = str_replace('minute', 'm', $uptime);
            $uptime = str_replace('seconds', 's', $uptime);
            $uptime = str_replace('second', 's', $uptime);
            $uptime = str_replace(' ', '', $uptime);
            $uptime = str_replace(',', ' ', $uptime);
            $uptime = str_replace(PHP_EOL, '', $uptime);
        }
        
        return $uptime;
    }

}
