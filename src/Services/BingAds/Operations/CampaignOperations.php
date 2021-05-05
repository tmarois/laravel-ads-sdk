<?php

namespace LaravelAds\Services\BingAds\Operations;

use LaravelAds\Services\BingAds\Operations\Campaign;
use LaravelAds\Services\BingAds\Service;


use Microsoft\BingAds\V13\CampaignManagement\Campaign as CampaignProxy;
use Microsoft\BingAds\V13\CampaignManagement\CampaignStatus;

use Microsoft\BingAds\V13\CampaignManagement\GetCampaignsByIdsRequest;
use Microsoft\BingAds\V13\CampaignManagement\UpdateCampaignsRequest;

use Microsoft\BingAds\Auth\ServiceClient;
use Microsoft\BingAds\Auth\ServiceClientType;

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
    public function set($campaign)
    {
        $this->response = $campaign;

        // set up our request if we have not done this yet
        $this->request()->Id = $campaign->Id;

        return $this;
    }

    /**
     * get()
     *
     */
    public function get()
    {
        $this->set($this->sendRequest());

        return $this;
    }

    /**
     * save()
     *
     * Post your changes to Google Ads Server
     *
     */
    public function save($updateObject = true)
    {
        $serviceCall = $this->service->call(ServiceClientType::CampaignManagementVersion13);

        try
        {
            $campaign = $this->request();

            $request = new UpdateCampaignsRequest();
            $request->AccountId  = $this->service->getClientId();
            $request->CampaignId = $campaign->Id;
            $request->Campaigns = [$campaign];
            // $request->UpdateAudienceAdsBidAdjustment = true;
            // $request->ReturnInheritedBidStrategyTypes = true;

            $serverResponse = $serviceCall->GetService()->UpdateCampaigns($request);

            // lets update the current object
            if ($updateObject) $this->get();
        }
        catch(\Exception $e) {
            print $serviceCall->GetService()->__getLastRequest()."\n";
            print $serviceCall->GetService()->__getLastResponse()."\n";
        }

        return $this;
    }

    /**
     * sendRequest()
     *
     */
    protected function sendRequest()
    {
        $serviceCall = $this->service->call(ServiceClientType::CampaignManagementVersion13);

        try
        {
            $campaign = $this->request();

            $request = new GetCampaignsByIdsRequest();
            $request->AccountId = $this->service->getClientId();
            $request->CampaignIds = [$campaign->Id];

            return $serviceCall->GetService()->GetCampaignsByIds($request)->Campaigns->Campaign[0] ?? null;
        }
        catch(\Exception $e) {
            print $serviceCall->GetService()->__getLastRequest()."\n";
            print $serviceCall->GetService()->__getLastResponse()."\n";
        }

        return (new CampaignProxy());
    }

}
