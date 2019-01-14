<?php namespace LaravelAds\Services\BingAds\Operations;

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
     * getBid()
     *
     */
    public function getBid()
    {
        return $this->adGroup->CpcBid->Amount ?? null;
    }

    /**
     * getStatus()
     *
     * @return string
     *
     */
    public function getStatus()
    {
        return $this->adGroup->Status ?? null;
    }

    /**
     * getId()
     *
     * @return int
     *
     */
    public function getId()
    {
        return $this->adGroup->Id ?? null;
    }

    /**
     * getCampaignId()
     *
     * @return int
     *
     */
    public function getCampaignId()
    {
        return $this->adGroup->CampaignId ?? null;
    }

    /**
     * getName()
     *
     * @return string
     *
     */
    public function getName()
    {
        return $this->adGroup->Name ?? null;
    }

    /**
     * getBidType()
     *
     */
    public function getBidType()
    {
        $type = $this->adGroup->BiddingScheme->InheritedBidStrategyType ?? null;

        if ($type = 'EnhancedCpc') {
            return 'ECPC';
        }

        if ($type = 'ManualCpc') {
            return 'CPC';
        }

        if ($type = 'TargetCpa') {
            return 'CPA';
        }

        return $type;
    }

}
