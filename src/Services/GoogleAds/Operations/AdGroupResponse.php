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
     * getBid()
     *
     * This will get the bid that is currently active on the bid type
     *
     */
    public function getBid()
    {
        $bids = $this->adGroup->getBiddingStrategyConfiguration()->getBids() ?? [];

        $bidAmount = 0;

        foreach($bids as $bid)
        {
            if ($bid->getBidsType() == 'CpcBid' && $this->getBidType() == "MANUAL_CPC")
            {
                $bidAmount = $bid->getbid()->getMicroAmount();
                break;
            }

            if ($bid->getBidsType() == 'CpmBid' && $this->getBidType() == "MANUAL_CPM")
            {
                $bidAmount = $bid->getbid()->getMicroAmount();
                break;
            }

            if ($bid->getBidsType() == 'CpaBid' && $this->getBidType() == "TARGET_CPA")
            {
                $bidAmount = $bid->getbid()->getMicroAmount();
                break;
            }
        }

        return (($bidAmount) ? round( intval($bidAmount) / 1000000,2) : 0);
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
