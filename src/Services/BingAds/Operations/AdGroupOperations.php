<?php namespace LaravelAds\Services\BingAds\Operations;

use LaravelAds\Services\BingAds\Operations\AdGroup;
use LaravelAds\Services\BingAds\Service;

use Microsoft\BingAds\V12\CampaignManagement\GetAdGroupsByIdsRequest;
use Microsoft\BingAds\V12\CampaignManagement\UpdateAdGroupsRequest;
use Microsoft\BingAds\V12\CampaignManagement\AdGroup as AdGroupProxy;

use Microsoft\BingAds\Auth\ServiceClient;
use Microsoft\BingAds\Auth\ServiceClientType;

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
    public function set($adGroup)
    {
        $this->response = $adGroup;

        // set up our request if we have not done this yet
        $this->request()->Id = $adGroup->Id;

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
     * sendRequest()
     *
     */
    protected function sendRequest()
    {
        $serviceCall = $this->service->call(ServiceClientType::CampaignManagementVersion12);

        try
        {
            $adGroup = $this->request();

            $request = new GetAdGroupsByIdsRequest();
            $request->AccountId  = $this->service->getClientId();
            $request->CampaignId = $adGroup->CampaignId;
            $request->AdGroupIds = [$adGroup->Id];

            return $serviceCall->GetService()->GetAdGroupsByIds($request)->AdGroups->AdGroup[0] ?? null;
        }
        catch(\Exception $e) {
            print $serviceCall->GetService()->__getLastRequest()."\n";
            print $serviceCall->GetService()->__getLastResponse()."\n";
        }

        return (new AdGroupProxy());
    }

    /**
     * save()
     *
     */
    public function save($updateObject = true)
    {
        $serviceCall = $this->service->call(ServiceClientType::CampaignManagementVersion12);

        try
        {
            $adGroup = $this->request();

            $request = new UpdateAdGroupsRequest();
            $request->AccountId  = $this->service->getClientId();
            $request->CampaignId = $adGroup->CampaignId;
            $request->AdGroups = [$adGroup];
            // $request->UpdateAudienceAdsBidAdjustment = true;
            // $request->ReturnInheritedBidStrategyTypes = true;

            $serverResponse = $serviceCall->GetService()->UpdateAdGroups($request);

            // lets update the current object
            if ($updateObject) $this->get();
        }
        catch(\Exception $e) {
            print $serviceCall->GetService()->__getLastRequest()."\n";
            print $serviceCall->GetService()->__getLastResponse()."\n";
        }

        return $this;
    }
}
