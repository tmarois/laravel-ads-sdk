<?php

namespace LaravelAds\Services\GoogleAds;

use Illuminate\Support\Traits\Macroable;

use LaravelAds\Services\GoogleAds\Operations\Campaign;
use LaravelAds\Services\GoogleAds\Operations\AdGroup;
use LaravelAds\Services\GoogleAds\Operations\OfflineConversions;

use LaravelAds\Services\GoogleAds\Reports;
use LaravelAds\Services\GoogleAds\Fetch;

use Google\AdsApi\AdWords\v201809\cm\Campaign as CampaignProxy;
use Google\AdsApi\AdWords\v201809\cm\AdGroup as AdGroupProxy;

use Google\AdsApi\Common\Configuration;
use Google\AdsApi\Common\OAuth2TokenBuilder;
use Google\AdsApi\AdWords\AdWordsServices;
use Google\AdsApi\AdWords\AdWordsSessionBuilder;

class Service
{
    use Macroable;

    /**
     * $clientIds
     *
     * @var array
     */
    protected $clientId = null;

    /**
     * $session
     *
     *
     */
    protected $session;

    /**
     * $config
     *
     *
     */
    protected $config;

    /**
     * with()
     *
     * Sets the client ids
     *
     * @return self
     */
    public function with($clientId) {
        $this->clientId = $clientId;
        return $this;
    }

    /**
     * getClientId()
     *
     * @return string
     */
    public function getClientId() {
        return $this->clientId;
    }

    /**
     * fetch()
     *
     *
     */
    public function fetch() {
        return (new Fetch($this));
    }

    /**
     * reports()
     *
     *
     */
    public function reports($dateFrom, $dateTo) {
        return (new Reports($this))->setDateRange($dateFrom, $dateTo);
    }

    /**
     * call()
     *
     *
     */
    public function call($service) {
        return (new AdWordsServices())->get($this->session(), $service);
    }

    /**
     * offlineConversionImport()
     *
     *
     */
    public function offlineConversionImport(array $conversions = []) {
        return (new OfflineConversions($this))->addBulk($conversions);
    }

    /**
     * adGroup()
     *
     * @reference
     * https://github.com/googleads/googleads-php-lib/blob/master/examples/AdWords/v201809/BasicOperations/UpdateAdGroup.php
     *
     * @return AdGroup
     */
    public function adGroup($adGroup)
    {
        if ($adGroup instanceof AdGroupProxy) {
            return (new AdGroup($this))->set($adGroup);
        } else {
            return (new AdGroup($this))->setId($adGroup)->get();
        }
    }

    /**
     * campaign()
     *
     * @return Campaign
     */
    public function campaign($campaign)
    {
        if ($campaign instanceof CampaignProxy) {
            return (new Campaign($this))->set($campaign);
        } else {
            return (new Campaign($this))->setId($campaign)->get();
        }
    }

    /**
     * session()
     *
     * Retrieves an instance of a session or creates a new one
     *
     * @return object $this->session
     */
    public function session()
    {
        if (!$this->session) {
            $this->session = (new AdWordsSessionBuilder())
                ->from($this->configuration())
                ->withOAuth2Credential($this->oAuth2credentials())
                ->withClientCustomerId($this->getClientId())
                ->build();
        }

        return $this->session;
    }


    /**
     * oAuth2credentials()
     *
     */
    protected function oAuth2credentials($env = null)
    {
        return (new OAuth2TokenBuilder())
            ->from($this->configuration())
            ->build();
    }

    /**
    * Configuration
    *
    * @return Configuration
    */
    public function configuration($config = [])
    {
        if (!$config) {
            // use laravel config
            $config = config('google-ads');

            // check if config already exist
            if ($this->config) {
                return $this->config;
            }
        }

        // create a new config
        return ($this->config = (new Configuration($config)));
    }
}
