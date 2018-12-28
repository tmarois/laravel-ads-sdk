<?php namespace LaravelAds\Services\GoogleAds;

use LaravelAds\Services\GoogleAds\Reports;

use Google\AdsApi\AdWords\AdWordsServices;
use Google\AdsApi\Common\Configuration;
use Google\AdsApi\Common\OAuth2TokenBuilder;
use Google\AdsApi\AdWords\AdWordsSessionBuilder;

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
     * reports()
     *
     *
     */
    public function reports($dateFrom, $dateTo)
    {
        return (new Reports($this))->setDateRange($dateFrom, $dateTo);
    }

    /**
     * service()
     *
     *
     */
    public function service($service)
    {
        return (new AdWordsServices())->get($this->session(), $service);
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
        if (!$this->session)
        {
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
    protected function configuration()
    {
        $config = config('google-ads');

        return (new Configuration($config));
    }

}
