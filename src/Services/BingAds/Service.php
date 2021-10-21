<?php

namespace LaravelAds\Services\BingAds;

use Illuminate\Support\Traits\Macroable;

use LaravelAds\Services\BingAds\Fetch;
use LaravelAds\Services\BingAds\Reports;
use Microsoft\BingAds\Auth\ServiceClient;

use Microsoft\BingAds\Auth\ApiEnvironment;
use Microsoft\BingAds\Auth\AuthorizationData;
use Microsoft\BingAds\Auth\ServiceClientType;
use Microsoft\BingAds\Auth\OAuthWebAuthCodeGrant;
use LaravelAds\Services\BingAds\Operations\AdGroup;

use LaravelAds\Services\BingAds\Operations\Campaign;
use LaravelAds\Services\BingAds\Operations\Customer;
use Microsoft\BingAds\Auth\OAuthTokenRequestException;
use LaravelAds\Services\BingAds\Operations\AdGroupRequest;
use Microsoft\BingAds\Auth\OAuthDesktopMobileAuthCodeGrant;
use LaravelAds\Services\BingAds\Operations\OfflineConversions;
use Microsoft\BingAds\V13\CampaignManagement\AdGroup as AdGroupProxy;
use Microsoft\BingAds\V13\CampaignManagement\Campaign as CampaignProxy;

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
     * $customerId
     *
     * @var array
     */
    protected $customerId = null;

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

    protected $environment = ApiEnvironment::Production;

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
     * withCustomerId()
     *
     * Sets the customer id
     *
     * @return self
     */
    public function withCustomerId($customerId) {
        $this->customerId = $customerId;
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
     * getCustomerId()
     *
     * @return string
     */
    public function getCustomerId() {
        return $this->customerId;
    }

    /**
     * setEnvironment()
     *
     * Sets the Bing API environment
     *
     * @return self
     */
    public function setEnvironment($env) {
        $this->environment = $env;
        return $this;
    }

    /**
     * getEnvironment()
     *
     * Get the current Bing API environment
     *
     * @return string
     */
    public function getEnvironment() {
        return $this->environment;
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
     * call()
     *
     *
     */
    public function call($service) {
        $serviceClient = (new ServiceClient($service, $this->session(), $this->environment));
        $serviceClient->SetAuthorizationData($this->session());
        return $serviceClient;
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
     *
     * @return AdGroupOperation
     */
    public function adGroup($adGroup, $campaignId = null)
    {
        if ($adGroup instanceof \stdClass) {
            return (new AdGroup($this))->set($adGroup);
        }

        return (new AdGroup($this))->setId($adGroup)->setCampaignId($campaignId)->get();
    }

    /**
     * campaign()
     *
     * @return Campaign
     */
    public function campaign($campaign)
    {
        if ($campaign instanceof \stdClass) {
            return (new Campaign($this))->set($campaign);
        }

        return (new Campaign($this))->setId($campaign)->get();
    }

    /**
     * campaign()
     *
     * @return Customer
     */
    public function customer($customer)
    {
        if ($customer instanceof \stdClass) {
            return (new Customer($this))->set($customer);
        }

        return (new Customer($this))->setId($customer)->get();
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
            $config = config('bing-ads');

            // check if config already exist
            if ($this->config) {
                return $this->config;
            }
        }

        // create a new config
        return ($this->config = (($config)));
    }

    /**
     * session()
     *
     *
     */
    public function session()
    {
        if (!$this->session) {
            $config = $this->configuration();

            $AuthorizationData = (new AuthorizationData())
                ->withAccountId($this->getClientId())
                ->withAuthentication($this->oAuthcredentials($config))
                ->withDeveloperToken($config['developerToken']);

            // Add Customer Id (OPTIONAL)
            if ($this->getCustomerId()) {
                $AuthorizationData->withCustomerId($this->getCustomerId());
            }

            try {
                $AuthorizationData->Authentication->RequestOAuthTokensByRefreshToken($config['refreshToken']);
            } catch (OAuthTokenRequestException $e) {
                // printf("Error: %s\n", $e->Error);
                // printf("Description: %s\n", $e->Description);
                // AuthHelper::RequestUserConsent();
            }

            $this->session = $AuthorizationData;
        }

        return $this->session;
    }

    /**
     * oAuth2credentials()
     *
     */
    protected function oAuthcredentials($config)
    {
        return (new OAuthDesktopMobileAuthCodeGrant())
                ->withClientSecret($config['clientSecret'])
                ->withClientId($config['clientId']);
    }
}
