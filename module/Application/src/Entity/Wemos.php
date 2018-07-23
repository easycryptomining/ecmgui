<?php

namespace Application\Entity;

class Wemos implements WemosInterface {

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
    public $type;

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
     * @return bool
     */
    public function ping() {
        $result = false;
        if (!empty($this->name)) {
            $pingresult = exec("ping -c 1 -s 64 $this->name", $outcome, $status);
            if (0 == $status) {
                $result = true;
            }
        }
        return $result;
    }

    /**
     * @return array
     */
    public function power() {
        $power = 0;
        $device = \a15lam\PhpWemo\Discovery::lookupDevice('id', $this->name);
        if (!empty($device)) {
            $client = new \a15lam\PhpWemo\WemoClient($device['ip'], $device['port']);
            $deviceClass = $device['class_name'];
            $myDevice = new $deviceClass($device['id'], $client);
            // Get power consomption
            $params = $myDevice->getParams();
            $parts = explode('|', $params);
            $power = round($parts[7] / 1000);
        }
        return $power;
    }
    
    /**
     * @return integer
     */
    public function state() {
        $state = 0;
        $device = \a15lam\PhpWemo\Discovery::lookupDevice('id', $this->name);
        if (!empty($device)) {
            $client = new \a15lam\PhpWemo\WemoClient($device['ip'], $device['port']);
            $deviceClass = $device['class_name'];
            $myDevice = new $deviceClass($device['id'], $client);
            $state = $myDevice->state();
        }
        return $state;
    }
    
    /**
     * @return integer
     */
    public function toggle() {
        $state = 0;
        $device = \a15lam\PhpWemo\Discovery::lookupDevice('id', $this->name);
        if (!empty($device)) {
            $client = new \a15lam\PhpWemo\WemoClient($device['ip'], $device['port']);
            $deviceClass = $device['class_name'];
            $myDevice = new $deviceClass($device['id'], $client);
            $state = $myDevice->state();
            if ($state == 1 || $state == 8) {
                $myDevice->Off();
            } else {
                $myDevice->On();
            }
            $state = $myDevice->state();
        }
        return $state;
    }

}
