<?php

namespace LaravelAds\Services\GoogleAds\Operations;

use LaravelAds\Services\GoogleAds\Operations\AdGroupOperations;

use Google\AdsApi\AdWords\v201809\cm\AdGroup as AdGroupProxy;
use Google\AdsApi\AdWords\v201809\cm\Money;
use Google\AdsApi\AdWords\v201809\cm\BiddingStrategyConfiguration;
use Google\AdsApi\AdWords\v201809\cm\CpcBid;
use Google\AdsApi\AdWords\v201809\cm\CpaBid;

// use Google\AdsApi\AdWords\v201809\cm\CpmBid;

class AdGroup extends AdGroupOperations
{
    /**
     * getId()
     *
     */
    public function getId()
    {
        return $this->response()->getId();
    }

    /**
     * setId()
     *
     * @param int $id
     *
     */
    public function setId($id)
    {
        $this->request()->setId($id);

        return $this;
    }

    /**
     * getName()
     *
     * @return string
     *
     */
    public function getName()
    {
        return $this->response()->getName();
    }

    /**
     * setName()
     *
     * @param string $name
     *
     */
    public function setName($name)
    {
        $this->request()->setName($name);

        return $this;
    }

    /**
     * getCampaignId()
     *
     * @param int $id
     *
     */
    public function getCampaignId()
    {
        return $this->response()->getCampaignId();
    }

    /**
     * setCampaignId()
     *
     * @param int $id
     *
     */
    public function setCampaignId($id)
    {
        $this->request()->setCampaignId($id);

        return $this;
    }

    /**
     * getStatus()
     *
     * @return string
     *
     */
    public function getStatus()
    {
        $status = strtoupper($this->response()->getStatus());

        switch ($status) {
            case 'ENABLED': return 'ENABLED'; break;
            case 'PAUSED': return 'PAUSED'; break;
            case 'REMOVED': return 'DELETED'; break;
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
        $status = strtoupper($status);

        if (in_array($status, ['ENABLED','PAUSED'])) {
            $this->request()->setStatus($status);
        }

        return $this;
    }

    /**
     * getType()
     *
     * @reference
     * https://github.com/googleads/googleads-php-lib/blob/master/src/Google/AdsApi/AdWords/v201809/cm/AdGroup.php
     *
     * @return string
     *
     */
    public function getType()
    {
        return strtoupper($this->response()->getAdGroupType());
    }

    /**
     * getBidStrategy()
     *
     * @return string
     *
     */
    public function getBidStrategy()
    {
        $type = $this->response()->getBiddingStrategyConfiguration()->getBiddingStrategyType() ?? 'UNKNOWN';

        switch ($type) {
            case 'MANUAL_CPC':

                $isEnhanced = false;

                $scheme = $this->response()->getBiddingStrategyConfiguration()->getBiddingScheme() ?? null;
                if ($scheme) {
                    $isEnhanced = $this->response()->getBiddingStrategyConfiguration()->getBiddingScheme()->getEnhancedCpcEnabled() ?? false;
                }

                if ($isEnhanced == true) {
                    return 'ECPC';
                }

                return 'CPC';

            break;

            case 'TARGET_CPA':
                return 'CPA';
                break;

            default:
        }

        return $type;
    }


    /**
     * getBid()
     *
     * This will get the bid that is currently active on the bid type
     *
     */
    public function getBid()
    {
        $bids = $this->response()->getBiddingStrategyConfiguration()->getBids() ?? [];

        $bidAmount = 0;

        foreach ($bids as $bid) {
            if ($bid->getBidsType() == 'CpcBid' && ($this->getBidStrategy() == "CPC" || $this->getBidStrategy() == "ECPC")) {
                $bidAmount = $bid->getbid()->getMicroAmount();
                break;
            }

            if ($bid->getBidsType() == 'CpaBid' && $this->getBidStrategy() == "CPA") {
                $bidAmount = $bid->getbid()->getMicroAmount();
                break;
            }
        }

        return (($bidAmount) ? round(intval($bidAmount) / 1000000, 2) : 0);
    }


    /**
     * setBid()
     *
     * @reference
     * https://github.com/googleads/googleads-php-lib/blob/master/examples/AdWords/v201809/BasicOperations/UpdateAdGroup.php
     *
     * @param float $amount
     *
     */
    public function setBid($amount)
    {
        if ($this->getBidStrategy() == 'CPC' || $this->getBidStrategy() == 'ECPC') {
            $bid = new CpcBid();

            $money = (new Money())->setMicroAmount($amount*1000000);
            $bid->setBid($money);

            $biddingStrategyConfiguration = new BiddingStrategyConfiguration();
            $biddingStrategyConfiguration->setBids([$bid]);

            $this->request()->setBiddingStrategyConfiguration($biddingStrategyConfiguration);
        }

        return $this;
    }
}
