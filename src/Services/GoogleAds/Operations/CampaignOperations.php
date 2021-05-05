<?php

namespace LaravelAds\Services\GoogleAds\Operations;

use LaravelAds\Services\GoogleAds\Operations\Campaign;
use LaravelAds\Services\GoogleAds\Service;

use Google\AdsApi\AdWords\v201809\cm\Campaign as CampaignProxy;
use Google\AdsApi\AdWords\v201809\cm\CampaignOperation;
use Google\AdsApi\AdWords\v201809\cm\CampaignService;

use Google\AdsApi\AdWords\v201809\cm\Budget;
use Google\AdsApi\AdWords\v201809\cm\BudgetService;
use Google\AdsApi\AdWords\v201809\cm\BudgetOperation;
use Google\AdsApi\AdWords\v201809\cm\BudgetBudgetDeliveryMethod;

use Google\AdsApi\AdWords\v201809\cm\Operator;

class CampaignOperations
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

        $this->request = new CampaignProxy();
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
    public function set(CampaignProxy $campaign)
    {
        $this->response = $campaign;

        // set up our request if we have not done this yet
        $this->request()->setId($campaign->getId());

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
        $campaign = $this->request();

        if ($save == true && $campaign->getBudget()) {
            $this->saveBudget();
        }

        $operation = new CampaignOperation();
        $operation->setOperand($campaign);
        $operation->setOperator(Operator::SET);

        $serverResponse = ($this->service->call(CampaignService::class))->mutate([$operation]);

        return ($serverResponse->getValue()[0] ?? null);
    }

    /**
     * saveBudget()
     *
     */
    protected function saveBudget()
    {
        $campaign = $this->request();

        $operation = new BudgetOperation();
        $operation->setOperand($campaign->getBudget());
        $operation->setOperator(Operator::SET);
        $operations[] = $operation;

        $serverResponse = ($this->service->call(BudgetService::class))->mutate($operations);

        return $serverResponse->getValue()[0] ?? null;
    }
}
