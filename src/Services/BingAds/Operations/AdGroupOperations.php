<?php

namespace LaravelAds\Services\BingAds\Operations;

use LaravelAds\Services\BingAds\Service;
use Microsoft\BingAds\Auth\ServiceClient;

use Microsoft\BingAds\Auth\ServiceClientType;
use LaravelAds\Services\BingAds\Operations\AdGroup;
use LaravelAds\Services\BingAds\Operations\Operation;

use Microsoft\BingAds\V13\CampaignManagement\UpdateAdGroupsRequest;
use Microsoft\BingAds\V13\CampaignManagement\AdGroup as AdGroupProxy;
use Microsoft\BingAds\V13\CampaignManagement\GetAdGroupsByIdsRequest;

class AdGroupOperations extends Operation
{
    /**
     * __construct()
     *
     */
    public function __construct(Service $service = null) {
        $this->service = $service;
        $this->request = new AdGroupProxy();
    }

    /**
     * sendRequest()
     *
     */
    protected function sendRequest()
    {
        $serviceCall = $this->service->call(ServiceClientType::CampaignManagementVersion13);

        try {
            $adGroup = $this->request();

            $request = new GetAdGroupsByIdsRequest();
            $request->AccountId  = $this->service->getClientId();
            $request->CampaignId = $adGroup->CampaignId;
            $request->AdGroupIds = [$adGroup->Id];

            return $serviceCall->GetService()->GetAdGroupsByIds($request)->AdGroups->AdGroup[0] ?? null;
        } catch (\Exception $e) {
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
        $serviceCall = $this->service->call(ServiceClientType::CampaignManagementVersion13);

        try {
            $adGroup = $this->request();

            $request = new UpdateAdGroupsRequest();
            $request->AccountId  = $this->service->getClientId();
            $request->CampaignId = $adGroup->CampaignId;
            $request->AdGroups = [$adGroup];
            // $request->UpdateAudienceAdsBidAdjustment = true;
            // $request->ReturnInheritedBidStrategyTypes = true;

            $serverResponse = $serviceCall->GetService()->UpdateAdGroups($request);

            // lets update the current object
            if ($updateObject) {
                $this->get();
            }
        } catch (\Exception $e) {
            print $serviceCall->GetService()->__getLastRequest()."\n";
            print $serviceCall->GetService()->__getLastResponse()."\n";
        }

        return $this;
    }
}
