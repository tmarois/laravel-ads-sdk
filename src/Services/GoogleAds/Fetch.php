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
     *
     * @return object Collection
     */
    public function getCampaigns()
    {
        $selector = new Selector();
        $selector->setFields([
            'Id',
            'Name',
            'Amount',
            'CampaignStatus',
            'BiddingStrategyType',
            'AdvertisingChannelType'
        ]);

        $page = $this->service->service(CampaignService::class)->get($selector);
        $items = $page->getEntries();

        $r = [];
        foreach ($items as $item)
        {
            $bidType = $item->getBiddingStrategyConfiguration()->getBiddingStrategyType() ?? '';
            $budget = $item->getBudget()->getAmount()->getMicroAmount() ?? 0;

            $r[] = [
                'id' => $item->getId(),
                'name' => $item->getName(),
                'status' => $item->getStatus(),
                'channel' => $item->getAdvertisingChannelType(),
                'budget' => ($budget) ? round( intval($budget) / 1000000,2) : 0,
                'bid_type' => $bidType
            ];
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
    public function getAdGroups()
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

        $page  = $this->service->service(AdGroupService::class)->get($selector);
        $items = $page->getEntries();

        $r = [];
        foreach($items as $item)
        {
            $adgroup = (new AdGroupResponse($item));

            $r[] = [
                'id' => $adgroup->getId(),
                'name' => $adgroup->getName(),
                'status' => $adgroup->getStatus(),
                'campaign_id' => $adgroup->getCampaignId(),
                'type' => $adgroup->getAdGroupType(),
                'bid_type' => $adgroup->getBidType(),
                'bid' => $adgroup->getBid()
            ];
        }

        return collect($r);
    }

}
