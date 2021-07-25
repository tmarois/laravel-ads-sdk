<?php

namespace LaravelAds\Services\FacebookAds;

use LaravelAds\Services\FacebookAds\Service;

use FacebookAds\Object\AdAccount;
use FacebookAds\Object\Fields\AdSetFields;
use FacebookAds\Object\Fields\CampaignFields;

class Fetch
{
    /**
     * @var LaravelAds\Services\FacebookAds\Service
     */
    protected $service = null;

    /**
     * __construct()
     *
     * @param LaravelAds\Services\FacebookAds\Service $service
     */
    public function __construct(Service $service) {
        $this->service = $service;
    }

    /**
     * getCampaigns()
     *
     * @reference
     * https://developers.facebook.com/docs/marketing-api/reference/ad-account/campaigns/
     * 
     * allowed fields
     * https://github.com/facebook/facebook-php-business-sdk/blob/master/src/FacebookAds/Object/Fields/CampaignFields.php
     * 
     * @param array $fields
     * @param array $params
     * @return object Collection
     */
    public function getCampaigns($fields = [], $params = [])
    {
        // set the default fields if none are set
        if (!$fields) {
            $fields = [
                CampaignFields::ACCOUNT_ID,
                CampaignFields::ID,
                CampaignFields::NAME,
                CampaignFields::STATUS,
                CampaignFields::BID_STRATEGY,
                CampaignFields::DAILY_BUDGET
            ];
        }

        $account = new AdAccount('act_'.$this->service->getAccountId());
        $campaigns = $account->getCampaigns($fields, $params);
        $data = $campaigns->getResponse()->getContent()['data'] ?? [];

        return collect($data);
    }

    /**
     * getAdSets()
     * This is here to be compatible with google/bing
     *
     * @param array $fields
     * @param array $params
     * @return object Collection
     */
    public function getAdSets($fields = [], $params = []) {
        return $this->getAdGroups($fields, $params);
    }

    /**
     * getAdGroups()
     *
     * @reference 
     * https://developers.facebook.com/docs/marketing-api/reference/ad-account/adsets/
     * 
     * allowed fields
     * https://github.com/facebook/facebook-php-business-sdk/blob/master/src/FacebookAds/Object/Fields/AdSetFields.php
     *
     * @param array $fields
     * @param array $params
     * @return object Collection
     */
    public function getAdGroups($fields = [], $params = [])
    {
        // set the default fields if none are set
        if (!$fields) {
            $fields = [
                AdSetFields::ACCOUNT_ID,
                AdSetFields::CAMPAIGN_ID,
                AdSetFields::ID,
                AdSetFields::NAME,
                AdSetFields::STATUS,
                AdSetFields::DAILY_BUDGET,
                AdSetFields::BID_AMOUNT,
                AdSetFields::BID_STRATEGY,
            ];
        }

        $account = new AdAccount('act_'.$this->service->getAccountId());
        $adsets = $account->getAdSets($fields, $params);
        $data = $adsets->getResponse()->getContent()['data'] ?? [];
        
        return collect($data);
    }
}
