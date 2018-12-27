<?php namespace LaravelAds\GoogleAds;

use LaravelAds\GoogleAds\Reports;

use Google\AdsApi\Common\Configuration;
use Google\AdsApi\Common\OAuth2TokenBuilder;
use Google\AdsApi\AdWords\AdWordsSessionBuilder;

class GoogleAdsService
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
     * reports()
     *
     *
     */
    public function reports($dateFrom, $dateTo)
    {
        return (new Reports($this)->setDateRange($dateFrom, $dateTo));
    }

    /**
     * session()
     *
     * Retrieves an instance of a session or creates a new one
     *
     * @return object $this->session
     */
    protected function session()
    {
        if (!$this->session)
        {
            $this->session = (new AdWordsSessionBuilder())
                ->from($this->configuration())
                ->withOAuth2Credential($this->oAuth2credentials())
                ->withClientId($this->getClientId())
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
    protected function configuration()
    {
        $config = config('google-ads');

        return (new Configuration($config));
    }

}
