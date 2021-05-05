<?php

namespace LaravelAds\Services\GoogleAds\Operations;

use LaravelAds\Services\GoogleAds\Operations\CampaignOperations;

use Google\AdsApi\AdWords\v201809\cm\Campaign as CampaignProxy;

use Google\AdsApi\AdWords\v201809\cm\Money;
use Google\AdsApi\AdWords\v201809\cm\Budget;

class Campaign extends CampaignOperations
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
     * getBudget()
     *
     * @return int
     *
     */
    public function getBudget()
    {
        $microAmount = $this->response()->getBudget()->getAmount()->getMicroAmount();

        return (($microAmount) ? round(intval($microAmount) / 1000000, 2) : 0);
    }

    /**
     * getBudgetDelivery()
     *
     * @return string
     *
     */
    public function getBudgetDelivery()
    {
        return strtoupper($this->response()->getBudget()->getDeliveryMethod() ?? 'UNKNOWN');
    }

    /**
     * getChannelType()
     *
     * @return string
     *
     */
    public function getChannelType()
    {
        return strtoupper($this->response()->getAdvertisingChannelType() ?? 'UNKNOWN');
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
     * getTargetCpa()
     *
     * @return int
     *
     */
    public function getTargetCpa()
    {
        if ($this->getBidStrategy() == 'CPA') {
            $strategy =@ $this->response()->getBiddingStrategyConfiguration()->getBiddingScheme() ?? null;

            $amount = null;

            if ($strategy) {
                $tcpa =@ $strategy->getTargetCpa();
                if ($tcpa) {
                    $amount =@ ($tcpa->getMicroAmount() ?? 0);
                }
            }

            return (($amount) ? @round(intval($amount) / 1000000, 2) : 0);
        }

        return 0;
    }


    /**
     * setTargetCpa()
     *
     *
     */
    public function setTargetCpa($amount = 0)
    {
        if ($this->getBidStrategy() == 'CPA') {
            $money = new Money();
            $money->setMicroAmount($amount*1000000);

            $biddingConfig = $this->response()->getBiddingStrategyConfiguration();
            $biddingScheme = $biddingConfig->getBiddingScheme()->setTargetCpa($money);

            $this->request()->setBiddingStrategyConfiguration($biddingConfig);
        }

        return $this;
    }


    /**
     * setBudget()
     *
     * @param int $budget
     *
     */
    public function setBudget($amount = 0)
    {
        $money = new Money();
        $money->setMicroAmount($amount*1000000);

        $budget = new Budget();
        $budget->setBudgetId($this->response()->getBudget()->getBudgetId());
        $budget->setAmount($money);
        $budget->setDeliveryMethod($this->getBudgetDelivery());

        $this->request()->setBudget($budget);

        return $this;
    }
}
