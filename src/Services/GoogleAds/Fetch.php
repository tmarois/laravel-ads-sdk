<?php

namespace LaravelAds\Services\GoogleAds;

use LaravelAds\Services\GoogleAds\Service;
use LaravelAds\Services\GoogleAds\Operations\AdGroupResponse;

use Google\AdsApi\AdWords\v201809\cm\CampaignService;
use Google\AdsApi\AdWords\v201809\cm\AdGroupService;
use Google\AdsApi\AdWords\v201809\cm\OrderBy;
use Google\AdsApi\AdWords\v201809\cm\Paging;
use Google\AdsApi\AdWords\v201809\cm\Selector;
use Google\AdsApi\AdWords\v201809\cm\SortOrder;
use Google\AdsApi\AdWords\v201809\cm\Predicate;
use Google\AdsApi\AdWords\v201809\cm\PredicateOperator;

class Fetch
{
    /**
     * @var LaravelAds\Services\GoogleAds\Service
     */
    protected $service = null;

    /**
     * __construct()
     *
     * @param LaravelAds\Services\GoogleAds\Service $service
     */
    public function __construct(Service $service) {
        $this->service = $service;
    }

    /**
     * getCampaigns()
     *
     * @reference
     * https://github.com/googleads/googleads-php-lib/blob/master/src/Google/AdsApi/AdWords/v201809/cm/Campaign.php
     * https://developers.google.com/adwords/api/docs/reference/v201809/CampaignService.Campaign
     * https://developers.google.com/adwords/api/docs/appendix/selectorfields
     * https://github.com/googleads/googleads-php-lib/blob/master/examples/AdWords/v201809/BasicOperations/GetCampaigns.php
     *
     * @return object Collection
     */
    public function getCampaigns($rArray = true, $filters = [])
    {
        $selector = new Selector();
        $selector->setPaging(new Paging(0, 5000));
        $selector->setFields([
            'Id',
            'Name',
            'Amount',
            'CampaignStatus',
            'BiddingStrategyType',
            'AdvertisingChannelType',
            'TargetCpa'
        ]);

        if ($filters) {
            $predicates = [];

            foreach ($filters as $key=>$values) {
                $predicates[] = new Predicate($key, PredicateOperator::IN, $values);
            }

            $selector->setPredicates($predicates);
        }

        $totalNumEntries = 0;
        $entries = [];

        do {
            $page = $this->service->call(CampaignService::class)->get($selector);
            $items = $page->getEntries();

            if ($page->getEntries() !== null) {
                $totalNumEntries = $page->getTotalNumEntries();

                foreach ($items as $item) {
                    $campaign = $this->service->campaign($item);

                    if ($rArray) {
                        $entries[] = [
                            'id' => $campaign->getId(),
                            'name' => $campaign->getName(),
                            'status' => $campaign->getStatus(),
                            'channel' => $campaign->getChannelType(),
                            'budget' => $campaign->getBudget(),
                            'bid_strategy' => $campaign->getBidStrategy(),
                            'target_cpa' => $campaign->getTargetCpa()
                        ];
                    } else {
                        $entries[] = $campaign;
                    }
                }
            }

            $selector->getPaging()->setStartIndex(
                $selector->getPaging()->getStartIndex() + 5000
            );
        } while ($selector->getPaging()->getStartIndex() < $totalNumEntries);

        return collect($entries);
    }



    /**
     * getAdGroups()
     *
     * @reference
     * https://github.com/googleads/googleads-php-lib/blob/master/src/Google/AdsApi/AdWords/v201809/cm/AdGroup.php
     * https://developers.google.com/adwords/api/docs/reference/v201809/AdGroupService.AdGroup
     * https://github.com/googleads/googleads-php-lib/blob/master/examples/AdWords/v201809/BasicOperations/GetCampaigns.php
     *
     * @return object Collection
     */
    public function getAdGroups($rArray = true, $filters = [])
    {
        $selector = new Selector();
        $selector->setPaging(new Paging(0, 5000));
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

        if ($filters) {
            $predicates = [];

            foreach ($filters as $key=>$values) {
                $predicates[] = new Predicate($key, PredicateOperator::IN, $values);
            }

            $selector->setPredicates($predicates);
        }

        $totalNumEntries = 0;
        $entries = [];

        do {
            $page  = $this->service->call(AdGroupService::class)->get($selector);
            $items = $page->getEntries();

            if ($page->getEntries() !== null) {
                $totalNumEntries = $page->getTotalNumEntries();

                foreach ($items as $item) {
                    $adgroup = $this->service->adGroup($item);

                    if ($rArray) {
                        $entries[] = [
                            'id' => $adgroup->getId(),
                            'name' => $adgroup->getName(),
                            'status' => $adgroup->getStatus(),
                            'campaign_id' => $adgroup->getCampaignId(),
                            'type' => $adgroup->getType(),
                            'bid_strategy' => $adgroup->getBidStrategy(),
                            'bid' => $adgroup->getBid()
                        ];
                    } else {
                        $entries[] = $adgroup;
                    }
                }
            }

            $selector->getPaging()->setStartIndex(
                $selector->getPaging()->getStartIndex() + 5000
            );
        } while ($selector->getPaging()->getStartIndex() < $totalNumEntries);

        return collect($entries);
    }
}
