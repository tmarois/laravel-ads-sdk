<?php namespace LaravelAds\Services\GoogleAds;

use LaravelAds\Services\GoogleAds\Operations\AdGroupOperation;

class Operation
{
    /**
     * $service
     *
     */
    protected $service = null;

    /**
     * __construct()
     *
     *
     */
    public function __construct($service)
    {
        $this->service = $service;
    }

    /**
     * adGroup()
     *
     * @reference
     * https://github.com/googleads/googleads-php-lib/blob/master/examples/AdWords/v201809/BasicOperations/UpdateAdGroup.php
     *
     * @return AdGroupOperation
     */
    public function adGroup($id)
    {
        return (new AdGroupOperation($this->service, $id));
    }

}
