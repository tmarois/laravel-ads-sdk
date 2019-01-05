<?php namespace LaravelAds\Services\BingAds;

use LaravelAds\Services\BingAds\Reports;
use LaravelAds\Services\BingAds\Fetch;
use LaravelAds\Services\BingAds\Operations\AdGroupRequest;

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
     * serviceProxy()
     *
     *
     */
    public function serviceProxy($service)
    {
        return (new ServiceClient($service, $this->session(), 'Production'));
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
    public function adGroup()
    {
        return (new AdGroupRequest($this));
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
