<?php namespace LaravelAds\Services\BingAds;

use SoapVar;
use SoapFault;
use Exception;

use LaravelAds\Services\BingAds\Operations\Campaign;
use LaravelAds\Services\BingAds\Operations\AdGroup;

use Microsoft\BingAds\V12\CampaignManagement\GetCampaignsByAccountIdRequest;
use Microsoft\BingAds\V12\CampaignManagement\GetAdGroupsByCampaignIdRequest;

use Microsoft\BingAds\Auth\ServiceClientType;


class Fetch
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
     * getCampaigns()
     *
     *
     * @return object Collection
     */
    public function getCampaigns($returnArray = true)
    {
        $serviceCall = $this->service->call(ServiceClientType::CampaignManagementVersion12);

        $request = new GetCampaignsByAccountIdRequest();
        $request->AccountId = $this->service->getClientId();

        $r = [];

        try {
            $items = $serviceCall->GetService()->GetCampaignsByAccountId($request);
        }
        catch(\Exception $e) {
            return [];
        }

        if ($items && isset($items->Campaigns, $items->Campaigns->Campaign))
        {
            foreach ($items->Campaigns->Campaign as $item)
            {
                $campaign = $this->service->campaign($item);

                if ($returnArray)
                {
                    $r[] = [
                        'id' => $campaign->getId(),
                        'name' => $campaign->getName(),
                        'status' => $campaign->getStatus(),
                        'channel' => $campaign->getChannelType(),
                        'budget' => $campaign->getBudget(),
                        'bid_strategy' => $campaign->getBidStrategy(),
                        'target_cpa' => $campaign->getTargetCpa()
                    ];
                }
                else
                {
                    $r[] = $campaign;
                }
            }
        }

        return collect($r);

        // print $proxy->GetService()->__getLastRequest()."\n";
        // print $proxy->GetService()->__getLastResponse()."\n";
    }

    /**
     * getAdGroups()
     *
     *
     * @return object Collection
     */
    public function getAdGroups($returnArray = true)
    {
        $serviceCall = $this->service->call(ServiceClientType::CampaignManagementVersion12);

        $campaigns = $this->getCampaigns();

        $r = [];
        foreach($campaigns->all() as $campaign)
        {
            $request = new GetAdGroupsByCampaignIdRequest();
            $request->CampaignId = $campaign['id'];

            try
            {
                $items = $serviceCall->GetService()->GetAdGroupsByCampaignId($request);
            }
            catch(\Exception $e) {
                return [];
            }

            foreach($items->AdGroups->AdGroup as $item)
            {
                $adgroup = $this->service->adGroup($item);

                if ($returnArray)
                {
                    $r[] = [
                        'id' => $adgroup->getId(),
                        'name' => $adgroup->getName(),
                        'status' => $adgroup->getStatus(),
                        'campaign_id' => $request->CampaignId,
                        'type' => 'SEARCH',
                        'bid_strategy' => $adgroup->getBidStrategy(),
                        'bid' => $adgroup->getBid()
                    ];
                }
                else
                {
                    $r[] = $adgroup;
                }
            }
        }

        return collect($r);
    }

}


// GetCampaignsByAccountId
