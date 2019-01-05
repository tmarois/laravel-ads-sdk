<?php namespace LaravelAds\Services\GoogleAds\Operations;

class AdGroupResponse
{
    /**
     * $operations
     *
     */
    protected $adGroup = null;

    /**
     * __construct()
     *
     *
     */
    public function __construct($adGroup)
    {
        $this->adGroup = $adGroup;
    }

    /**
     * getBidType()
     *
     */
    public function getBidType()
    {
        return $this->adGroup->getBiddingStrategyConfiguration()->getBiddingStrategyType();
    }

    /**
     * getName()
     *
     * @reference
     * https://github.com/googleads/googleads-php-lib/blob/master/src/Google/AdsApi/AdWords/v201809/cm/AdGroup.php
     *
     * @return string
     *
     */
    public function getName()
    {
        return $this->adGroup->getName();
    }

    /**
     * getStatus()
     *
     * @reference
     * https://github.com/googleads/googleads-php-lib/blob/master/src/Google/AdsApi/AdWords/v201809/cm/AdGroup.php
     *
     * @return string
     *
     */
    public function getStatus()
    {
        return $this->adGroup->getStatus();
    }

    /**
     * getAdGroupType()
     *
     * @reference
     * https://github.com/googleads/googleads-php-lib/blob/master/src/Google/AdsApi/AdWords/v201809/cm/AdGroup.php
     *
     * @return string
     *
     */
    public function getAdGroupType()
    {
        return $this->adGroup->getAdGroupType();
    }

    /**
     * getId()
     *
     */
    public function getId()
    {
        return $this->adGroup->getId();
    }

    /**
     * getCampaignId()
     *
     */
    public function getCampaignId()
    {
        return $this->adGroup->getCampaignId();
    }

}
