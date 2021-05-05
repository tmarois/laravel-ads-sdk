<?php

namespace LaravelAds\Services\GoogleAds\Operations;

use LaravelAds\Services\GoogleAds\Operations\AdGroup;
use LaravelAds\Services\GoogleAds\Service;

use Google\AdsApi\AdWords\v201809\cm\AdGroup as AdGroupProxy;
use Google\AdsApi\AdWords\v201809\cm\AdGroupOperation;
use Google\AdsApi\AdWords\v201809\cm\AdGroupService;
use LaravelAds\Services\GoogleAds\Operations\AdGroupResponse;

use Google\AdsApi\AdWords\v201809\cm\Operator;

class AdGroupOperations
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
     * __construct()
     *
     */
    public function __construct(Service $service = null)
    {
        $this->service = $service;

        $this->request = new AdGroupProxy();
    }

    /**
     * request()
     *
     */
    public function request()
    {
        return $this->request;
    }

    /**
     * response()
     *
     */
    public function response()
    {
        return $this->response;
    }

    /**
     * set()
     *
     */
    public function set(AdGroupProxy $adGroup)
    {
        $this->response = $adGroup;

        // set up our request if we have not done this yet
        $this->request()->setId($adGroup->getId());

        return $this;
    }

    /**
     * get()
     *
     */
    public function get()
    {
        $this->set($this->sendRequest(false));

        return $this;
    }

    /**
     * save()
     *
     * Post your changes to Google Ads Server
     *
     */
    public function save()
    {
        $this->set($this->sendRequest(true));

        return $this;
    }

    /**
     * sendRequest()
     *
     */
    protected function sendRequest($save = false)
    {
        $adGroup = $this->request();

        $operation = new AdGroupOperation();
        $operation->setOperand($adGroup);
        $operation->setOperator(Operator::SET);

        $serverResponse = ($this->service->call(AdGroupService::class))->mutate([$operation]);

        return ($serverResponse->getValue()[0] ?? null);
    }
}
