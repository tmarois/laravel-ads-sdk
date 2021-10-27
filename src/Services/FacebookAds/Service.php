<?php

namespace LaravelAds\Services\FacebookAds;

use Illuminate\Support\Traits\Macroable;
use LaravelAds\Services\FacebookAds\Fetch;
use LaravelAds\Services\FacebookAds\Reports;

use FacebookAds\Api;

class Service
{
    use Macroable;

    /**
     * $accountId
     *
     * @var string
     */
    protected $accountId = null;

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
     * __construct()
     *
     *  We need to activate session for facebook
     *  on first load up, then all API requests should work
     *
     */
    public function __construct() {
        $this->session();
    }

    /**
     * with()
     *
     * Sets the client ids
     *
     * @return self
     */
    public function with($accountId) {
        $this->accountId = $accountId;
        return $this;
    }

    /**
     * getClientId()
     * This is here to be compatible with google/bing
     *
     * @return $accountId
     */
    public function getClientId() {
        return $this->getAccountId();
    }

    /**
     * getAccountId()
     *
     * @return $accountId
     */
    public function getAccountId() {
        return $this->accountId;
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
     * adGroup()
     * This is here to be compatible with google/bing
     *
     * @return AdGroup
     */
    public function adSet($adGroup) {
        return $this->adGroup($adGroup);
    }

    /**
     * adGroup()
     *
     * @return AdGroup
     */
    public function adGroup($adGroup)
    {
        // TODO: build out single adgroup (adset) objects
    }

    /**
     * campaign()
     *
     * @return Campaign
     */
    public function campaign($campaign)
    {
        // TODO: build out single campaign
    }

    /**
     * session()
     *
     * Sets the facebook instance
     *
     * @return object $this->session
     */
    public function session() {
        $config = $this->configuration();
        Api::init($config['app_id'], $config['app_secret'], $config['access_token']);
        $this->session = Api::instance();
        return $this->session;
    }

    /**
    * Configuration
    *
    * @return Configuration
    */
    public function configuration($config = [])
    {
        if (empty($config)) {
            // use laravel config
            $config = config('facebook-ads');

            // check if config already exist
            if ($this->config) {
                return $this->config;
            }
        }

        // create a new config
        return ($this->config = (($config)));
    }
}
