<?php

namespace LaravelAds\Services\BingAds\Operations;

use LaravelAds\Services\BingAds\Operations\AdGroupOperations;

use Microsoft\BingAds\V13\CampaignManagement\Bid;
use Microsoft\BingAds\V13\CampaignManagement\BiddingScheme;
use Microsoft\BingAds\V13\CampaignManagement\EnhancedCpcBiddingScheme;
use Microsoft\BingAds\V13\CampaignManagement\InheritFromParentBiddingScheme;
use Microsoft\BingAds\V13\CampaignManagement\ManualCpcBiddingScheme;

class AdGroup extends AdGroupOperations
{
    /**
     * getId()
     *
     */
    public function getId() {
        return $this->response()->Id ?? 0;
    }

    /**
     * setId()
     *
     * @param int $id
     *
     */
    public function setId($id) {
        $this->request()->Id = $id;
        return $this;
    }

    /**
     * getName()
     *
     * @return string
     *
     */
    public function getName() {
        return $this->response()->Name ?? '';
    }

    /**
     * setName()
     *
     * @param string $name
     *
     */
    public function setName($name) {
        $this->request()->Name = $name;
        return $this;
    }

    /**
     * getCampaignId()
     *
     * @param int $id
     *
     */
    public function getCampaignId() {
        return $this->request()->CampaignId;
    }

    /**
     * setCampaignId()
     *
     * @param int $id
     *
     */
    public function setCampaignId($id) {
        $this->request()->CampaignId = $id;
        return $this;
    }

    /**
     * getStatus()
     *
     * @reference
     * https://github.com/BingAds/BingAds-PHP-SDK/blob/master/src/V12/CampaignManagement/AdGroupStatus.php
     *
     * @return string
     *
     */
    public function getStatus()
    {
        $status = strtoupper($this->response()->Status ?? null);

        switch ($status) {
            case 'ACTIVE': return 'ENABLED'; break;
            case 'PAUSED': return 'PAUSED';  break;
            case 'DELETED': return 'DELETED'; break;
            default:
        }

        return $status;
    }

    /**
     * setStatus()
     *
     * @param string $status
     *
     */
    public function setStatus($status)
    {
        $status = ucfirst(strtolower($status));
        if ($status == 'Enabled') {
            $status = 'Active';
        }

        if (in_array($status, ['Active','Paused'])) {
            $this->request()->Status = $status;
        }

        return $this;
    }

    /**
     * getType()
     *
     * @return string
     *
     */
    public function getType() {
        return '';
    }

    /**
     * getBidStrategy()
     *
     * @return string
     *
     */
    public function getBidStrategy()
    {
        $type = $this->response()->BiddingScheme->InheritedBidStrategyType ?? 'UNKNOWN';

        switch ($type) {
            case 'EnhancedCpc': return 'ECPC'; break;
            case 'ManualCpc': return 'CPC'; break;
            case 'TargetCpa': return 'CPA'; break;
            default:
        }

        return $type;
    }


    /**
     * getBid()
     *
     */
    public function getBid()
    {
        if ($this->getBidStrategy() == 'CPC' || $this->getBidStrategy() == 'ECPC') {
            return $this->response()->CpcBid->Amount ?? 0;
        }

        if ($this->getBidStrategy() == 'CPA') {
            return $this->response()->CpaBid->Amount ?? 0;
        }

        return 0;
    }


    /**
     * setBid()
     *
     * https://github.com/BingAds/BingAds-PHP-SDK/blob/dc5c8fb9f9390ab14c102fdff68cb7592091b55c/samples/V12/KeywordsAds.php#L126
     *
     * @param float $amount
     *
     */
    public function setBid($amount)
    {
        if ($this->getBidStrategy() == 'CPC' || $this->getBidStrategy() == 'ECPC') {
            $this->request()->CpcBid = new Bid();
            $this->request()->CpcBid->Amount = $amount;
        }

        return $this;
    }
}
