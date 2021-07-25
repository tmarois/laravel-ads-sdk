<?php

namespace LaravelAds\Services\FacebookAds;

use FacebookAds\Object\AdAccount;
use FacebookAds\Object\AdCampaign;
use FacebookAds\Object\Values\InsightsPresets;
use FacebookAds\Object\Fields\AdsInsightsFields;

class Reports
{
    /**
     * $service
     *
     */
    protected $service = null;

    /**
     * $dateFrom
     *
     */
    protected $dateRange = [];

    /**
     * $fields
     *
     */
    protected $fields = [];

    /**
     * $params
     *
     */
    protected $params = [];

    /**
     * __construct()
     *
     *
     */
    public function __construct($service) {
        $this->service = $service;
    }

    /**
     * setDateRange()
     *
     *
     * @return self
     */
    public function setDateRange($dateFrom='', $dateTo='') {
        $this->dateRange[] = $dateFrom;
        $this->dateRange[] = $dateTo;
        return $this;
    }

    /**
     * setFields()
     *
     *
     * @return self
     */
    public function setFields($fields = []) {
        $this->fields = $fields;
        return $this;
    }

    /**
     * setParams()
     *
     * @reference https://developers.facebook.com/docs/marketing-api/insights/parameters/v11.0
     * @return self
     */
    public function setParams($params = []) {
        $this->params = $params;
        return $this;
    }

    /**
     * getAccountReport()
     *
     * @reference 
     * https://developers.facebook.com/docs/marketing-api/insights
     *
     */
    public function getAccountReport()
    {
        $api = new AdAccount('act_'.$this->service->getAccountId());

        $defaultParams = [
            'level' => 'account',
            'time_range' => [
                'since' => $this->dateRange[0],
                'until' => $this->dateRange[1],
            ]
        ];

        $params = array_merge($defaultParams, $this->params);

        $fields = [
            AdsInsightsFields::ACCOUNT_ID,
            AdsInsightsFields::ACCOUNT_NAME,
            AdsInsightsFields::IMPRESSIONS,
            AdsInsightsFields::CLICKS,
            AdsInsightsFields::CTR,
            AdsInsightsFields::CONVERSIONS,
            AdsInsightsFields::SPEND
        ];

        if (!empty($this->fields)) {
            $fields = $this->fields;
        }

        $insights = $api->getInsights($fields, $params);
        return collect($insights->getResponse()->getContent()['data'] ?? []);
    }


    /**
     * getCampaignReport()
     *
     * @reference 
     * https://developers.facebook.com/docs/marketing-api/insights
     * https://developers.facebook.com/docs/marketing-api/reference/ad-campaign-group/insights
     *
     */
    public function getCampaignReport()
    {
        $api = new AdAccount('act_'.$this->service->getAccountId());

        $defaultParams = [
            'level' => 'campaign',
            'time_range' => [
                'since' => $this->dateRange[0],
                'until' => $this->dateRange[1],
            ]
        ];

        $params = array_merge($defaultParams, $this->params);

        $fields = [
            AdsInsightsFields::ACCOUNT_ID,
            AdsInsightsFields::CAMPAIGN_ID,
            AdsInsightsFields::CAMPAIGN_NAME,
            AdsInsightsFields::IMPRESSIONS,
            AdsInsightsFields::CLICKS,
            AdsInsightsFields::CTR,
            AdsInsightsFields::CONVERSIONS,
            AdsInsightsFields::SPEND
        ];

        if (!empty($this->fields)) {
            $fields = $this->fields;
        }

        $insights = $api->getInsights($fields, $params);
        return collect($insights->getResponse()->getContent()['data'] ?? []);
    }

    /**
     * getAdSetReport()
     * This is here to be compatible with google/bing
     *
     */
    public function getAdSetReport() {
        return $this->getAdGroupReport();
    }

    /**
     * getAdGroupReport()
     *
     * @reference https://developers.facebook.com/docs/marketing-api/insights/parameters/v11.0
     * 
     */
    public function getAdGroupReport()
    {
        $api = new AdAccount('act_'.$this->service->getAccountId());

        $defaultParams = [
            'level' => 'adset',
            'time_range' => [
                'since' => $this->dateRange[0],
                'until' => $this->dateRange[1],
            ]
        ];

        $params = array_merge($defaultParams, $this->params);

        $fields = [
            AdsInsightsFields::ACCOUNT_ID,
            AdsInsightsFields::CAMPAIGN_ID,
            AdsInsightsFields::ADSET_ID,
            AdsInsightsFields::ADSET_NAME,
            AdsInsightsFields::IMPRESSIONS,
            AdsInsightsFields::CLICKS,
            AdsInsightsFields::CTR,
            AdsInsightsFields::CONVERSIONS,
            AdsInsightsFields::SPEND
        ];

        if (!empty($this->fields)) {
            $fields = $this->fields;
        }

        $insights = $api->getInsights($fields, $params);
        return collect($insights->getResponse()->getContent()['data'] ?? []);
    }
}
