<?php namespace LaravelAds\Services\BingAds;

use LaravelAds\Services\BingAds\Reports;
use LaravelAds\Services\BingAds\Fetch;

use LaravelAds\Services\BingAds\Operations\AdGroupRequest;
use LaravelAds\Services\BingAds\Operations\Campaign;
use LaravelAds\Services\BingAds\Operations\AdGroup;
use Microsoft\BingAds\V12\CampaignManagement\Campaign as CampaignProxy;
use Microsoft\BingAds\V12\CampaignManagement\AdGroup as AdGroupProxy;

use Microsoft\BingAds\Auth\OAuthDesktopMobileAuthCodeGrant;
use Microsoft\BingAds\Auth\OAuthWebAuthCodeGrant;
use Microsoft\BingAds\Auth\AuthorizationData;
use Microsoft\BingAds\Auth\OAuthTokenRequestException;
use Microsoft\BingAds\Auth\ApiEnvironment;
use Microsoft\BingAds\Auth\ServiceClient;
use Microsoft\BingAds\Auth\ServiceClientType;

class Service
{
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
     * with()
     *
     * Sets the client ids
     *
     * @return self
     */
    public function with($clientId)
    {
        $this->clientId = $clientId;

        return $this;
    }

    /**
     * getClientId()
     *
     * @return string
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * fetch()
     *
     *
     */
    public function fetch()
    {
        return (new Fetch($this));
    }

    /**
     * call()
     *
     *
     */
    public function call($service)
    {
        $serviceClient = (new ServiceClient($service, $this->session(), 'Production'));
        $serviceClient->SetAuthorizationData($this->session());

        return $serviceClient;
    }

    /**
     * reports()
     *
     *
     */
    public function reports($dateFrom, $dateTo)
    {
        return (new Reports($this))->setDateRange($dateFrom, $dateTo);
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
         else {
             return (new AdGroup($this))->setId($adGroup)->setCampaignId($campaignId)->get();
         }
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
        else {
            return (new Campaign($this))->setId($campaign)->get();
        }
    }

    /**
     * session()
     *
     *
     */
    public function session()
    {
        if (!$this->session)
        {
            $config = config('bing-ads');

            $AuthorizationData = (new AuthorizationData())
                ->withAccountId($this->getClientId())
                ->withAuthentication($this->oAuthcredentials($config))
                ->withDeveloperToken($config['developerToken']);

            try
            {
                $AuthorizationData->Authentication->RequestOAuthTokensByRefreshToken($config['refreshToken']);
            }
            catch(OAuthTokenRequestException $e)
            {
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
