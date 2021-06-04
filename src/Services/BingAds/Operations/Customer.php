<?php

namespace LaravelAds\Services\BingAds\Operations;

use LaravelAds\Services\BingAds\Operations\CustomerOperations;

class Customer extends CustomerOperations
{
    /**
     * getId()
     *
     */
    public function getId() {
        return $this->response()->Id ?? 0;
    }

    /**
     * setId()
     *
     * @param int $id
     *
     */
    public function setId($id) {
        $this->request()->Id = $id;
        return $this;
    }

    /**
     * getName()
     *
     * @return string
     *
     */
    public function getName() {
        return $this->response()->Name ?? '';
    }

    /**
     * setName()
     *
     * @param string $name
     *
     */
    public function setName($name) {
        $this->request()->Name = $name;
        return $this;
    }
}
