<?php namespace LaravelAds\Services\GoogleAds;

use LaravelAds\Services\GoogleAds\Operations\AdGroupResponse;

use Google\AdsApi\AdWords\v201809\cm\CampaignService;
use Google\AdsApi\AdWords\v201809\cm\AdGroupService;
use Google\AdsApi\AdWords\v201809\cm\OrderBy;
use Google\AdsApi\AdWords\v201809\cm\Paging;
use Google\AdsApi\AdWords\v201809\cm\Selector;
use Google\AdsApi\AdWords\v201809\cm\SortOrder;

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
     * @reference
     * https://github.com/googleads/googleads-php-lib/blob/master/src/Google/AdsApi/AdWords/v201809/cm/Campaign.php
     * https://developers.google.com/adwords/api/docs/reference/v201809/CampaignService.Campaign
     * https://developers.google.com/adwords/api/docs/appendix/selectorfields
     *
     * @return object Collection
     */
    public function getCampaigns($returnArray = true)
    {
        $selector = new Selector();
        $selector->setFields([
            'Id',
            'Name',
            'Amount',
            'CampaignStatus',
            'BiddingStrategyType',
            'AdvertisingChannelType',
            'TargetCpa'
        ]);

        $page = $this->service->call(CampaignService::class)->get($selector);
        $items = $page->getEntries();

        $r = [];
        foreach ($items as $item)
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

        return collect($r);
    }



    /**
     * getAdGroups()
     *
     * @reference
     * https://github.com/googleads/googleads-php-lib/blob/master/src/Google/AdsApi/AdWords/v201809/cm/AdGroup.php
     * https://developers.google.com/adwords/api/docs/reference/v201809/AdGroupService.AdGroup
     *
     * @return object Collection
     */
    public function getAdGroups($returnArray = true)
    {
        $selector = new Selector();
        $selector->setFields([
            'Id',
            'Name',
            'CampaignId',
            'Status',
            'BiddingStrategyType',
            'EnhancedCpcEnabled',
            'AdGroupType',
            'CpcBid',
            'CpmBid',
            'TargetCpaBid'
        ]);

        $page  = $this->service->call(AdGroupService::class)->get($selector);
        $items = $page->getEntries();

        $r = [];
        foreach($items as $item)
        {
            $adgroup = $this->service->adGroup($item);

            if ($returnArray)
            {
                $r[] = [
                    'id' => $adgroup->getId(),
                    'name' => $adgroup->getName(),
                    'status' => $adgroup->getStatus(),
                    'campaign_id' => $adgroup->getCampaignId(),
                    'type' => $adgroup->getType(),
                    'bid_strategy' => $adgroup->getBidStrategy(),
                    'bid' => $adgroup->getBid()
                ];
            }
            else
            {
                $r[] = $adgroup;
            }
        }

        return collect($r);
    }

}
