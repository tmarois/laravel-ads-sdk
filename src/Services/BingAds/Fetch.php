<?php

namespace LaravelAds\Services\BingAds;

use SoapVar;
use Exception;
use SoapFault;

use LaravelAds\Services\BingAds\Service;
use LaravelAds\Services\BingAds\Operations\AdGroup;
use LaravelAds\Services\BingAds\Operations\Campaign;

use Microsoft\BingAds\Auth\ServiceClientType;
use Microsoft\BingAds\V13\CustomerManagement\GetCustomersInfoRequest;
use Microsoft\BingAds\V13\CampaignManagement\GetAdGroupsByCampaignIdRequest;
use Microsoft\BingAds\V13\CampaignManagement\GetCampaignsByAccountIdRequest;

class Fetch
{
    /**
     * @var LaravelAds\Services\BingAds\Service
     */
    protected $service = null;

    /**
     * __construct()
     *
     * @param LaravelAds\Services\BingAds\Service $service
     */
    public function __construct(Service $service) {
        $this->service = $service;
    }

    /**
     * getCampaigns()
     *
     *
     * @return object Collection
     */
    public function getCampaigns($returnArray = true)
    {
        $serviceCall = $this->service->call(ServiceClientType::CampaignManagementVersion13);

        $request = new GetCampaignsByAccountIdRequest();
        $request->AccountId = $this->service->getClientId();

        $r = [];

        try {
            $items = $serviceCall->GetService()->GetCampaignsByAccountId($request);
        } catch (\Exception $e) {
            return [];
        }

        if ($items && isset($items->Campaigns, $items->Campaigns->Campaign)) {
            foreach ($items->Campaigns->Campaign as $item) {
                $campaign = $this->service->campaign($item);

                if ($returnArray) {
                    $r[] = [
                        'id' => $campaign->getId(),
                        'name' => $campaign->getName(),
                        'status' => $campaign->getStatus(),
                        'channel' => $campaign->getChannelType(),
                        'budget' => $campaign->getBudget(),
                        'bid_strategy' => $campaign->getBidStrategy(),
                        'target_cpa' => $campaign->getTargetCpa()
                    ];
                } else {
                    $r[] = $campaign;
                }
            }
        }

        return collect($r);
    }

    /**
     * getAdGroups()
     *
     *
     * @return object Collection
     */
    public function getAdGroups($returnArray = true)
    {
        $serviceCall = $this->service->call(ServiceClientType::CampaignManagementVersion13);

        $campaigns = $this->getCampaigns();

        $r = [];
        foreach ($campaigns->all() as $campaign) {
            $request = new GetAdGroupsByCampaignIdRequest();
            $request->CampaignId = $campaign['id'];

            try {
                $items = $serviceCall->GetService()->GetAdGroupsByCampaignId($request);

                foreach ($items->AdGroups->AdGroup as $item) {
                    $adgroup = $this->service->adGroup($item);

                    if ($returnArray) {
                        $r[] = [
                            'id' => $adgroup->getId(),
                            'name' => $adgroup->getName(),
                            'status' => $adgroup->getStatus(),
                            'campaign_id' => $request->CampaignId,
                            'type' => 'SEARCH',
                            'bid_strategy' => $adgroup->getBidStrategy(),
                            'bid' => $adgroup->getBid()
                        ];
                    } else {
                        $r[] = $adgroup;
                    }
                }
            } catch (\Exception $e) {
                continue;
            }
        }

        return collect($r);
    }

    public function getCustomers($returnArray = true)
    {
        $serviceCall = $this->service->call(ServiceClientType::CustomerManagementVersion13);

        $request = new GetCustomersInfoRequest();
        $request->CustomerNameFilter = '';
        $request->TopN = 100;

        try {
            $items = $serviceCall->GetService()->GetCustomersInfo($request);

            foreach ($items->CustomersInfo->CustomerInfo as $item) {
                $customer = $this->service->customer($item);

                if ($returnArray) {
                    $r[] = [
                        'id' => $customer->getId(),
                        'name' => $customer->getName(),
                    ];
                } else {
                    $r[] = $customer;
                }
            }
        } catch (\SoapFault $e) {
            var_dump($e->detail);
        } catch (\Exception $e) {
            var_dump($e->detail);
        }

        return collect($r);
    }
}
