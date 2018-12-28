<?php namespace LaravelAds;

use LaravelAds\Services\GoogleAds\Service AS GoogleAdsService;
use LaravelAds\Services\BingAds\Service AS BingAdsService;

class LaravelAds
{
    /**
     * service()
     *
     * @return static method
     */
    public static function service($service)
    {
        if ($service == 'GoogleAds')
        {
            return static::gAds();
        }

        if ($service == 'BingAds')
        {
            return static::bAds();
        }

        if ($service == 'FacebookAds')
        {
            return static::fAds();
        }
    }

    /**
     * gAds()
     *
     * Google Ads
     *
     */
    protected static function gAds()
    {
        return (new GoogleAdsService());
    }

    /**
     * bAds()
     *
     * Bind Ads
     *
     */
    protected static function bAds()
    {
        return (new BingAdsService());
    }

    /**
     * fAds()
     *
     * Facebook Ads
     *
     */
    protected static function fAds()
    {
        return false;
    }

}
