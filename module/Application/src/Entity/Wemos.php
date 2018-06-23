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

}
