<?php

namespace LaravelAds\Services\BingAds\Operations;

abstract class Operation
{
    /**
     * $service
     *
     */
    protected $service = null;

    /**
     * $campaignRequest
     *
     */
    protected $request = null;

    /**
     * $campaignResponse
     *
     */
    protected $response = null;

    /**
     * request()
     *
     */
    public function request() {
        return $this->request;
    }

    /**
     * response()
     *
     */
    public function response() {
        return $this->response;
    }

    /**
     * set()
     *
     */
    public function set($entity) {
        $this->response = $entity;
        // set up our request if we have not done this yet
        $this->request()->Id = $entity->Id;
        return $this;
    }

    /**
     * get()
     *
     */
    public function get() {
        $this->set($this->sendRequest());
        return $this;
    }
}
