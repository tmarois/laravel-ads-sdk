<?php

namespace LaravelAds\Services\BingAds\Operations;

use LaravelAds\Services\BingAds\Operations\CampaignOperations;

use SoapVar;

use Microsoft\BingAds\Auth\ServiceClient;
use Microsoft\BingAds\Auth\ServiceClientType;

use Microsoft\BingAds\V13\CampaignManagement\TargetCpaBiddingScheme;

class Campaign extends CampaignOperations
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
     * getStatus()
     *
     * @return string
     *
     */
    public function getStatus()
    {
        $status = strtoupper($this->response()->Status ?? 'UNKNOWN');

        switch ($status) {
            case 'ACTIVE': return 'ENABLED'; break;
            case 'BUDGETPAUSED': return 'ENABLED'; break;
            case 'BUDGETANDMANUALPAUSED': return 'PAUSED';  break;
            case 'PAUSED': return 'PAUSED';  break;
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
     * getBudget()
     *
     * @return int
     *
     */
    public function getBudget()
    {
        return $this->response()->DailyBudget;
    }

    /**
     * getBudgetDelivery()
     *
     * @return string
     *
     */
    public function getBudgetDelivery()
    {
        $type = strtoupper($this->response()->BudgetType ?? 'UNKNOWN');

        switch ($type) {
            case 'DAILYBUDGETACCELERATED': return 'ACCELERATED'; break;
            case 'DAILYBUDGETSTANDARD': return 'STANDARD';  break;
            default:
        }

        return $type;
    }

    /**
     * getChannelType()
     *
     * @return string
     *
     */
    public function getChannelType()
    {
        return strtoupper($this->response()->CampaignType ?? 'UNKNOWN');
    }

    /**
     * getBidStrategy()
     *
     * @return string
     *
     */
    public function getBidStrategy()
    {
        $type = strtoupper($this->response()->BiddingScheme->Type ?? 'UNKNOWN');

        switch ($type) {
            case 'ENHANCEDCPC': return 'ECPC'; break;
            case 'MANUALCPC': return 'CPC'; break;
            case 'TARGETCPA': return 'CPA'; break;
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
            return $this->response()->BiddingScheme->TargetCpa ?? 0;
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
            $biddingScheme = (new TargetCpaBiddingScheme());
            $biddingScheme->Type = 'TargetCpa';
            $biddingScheme->TargetCpa = $amount;

            // why? this is horrible. I hope Bing learns eventually...
            $this->request()->BiddingScheme = new SoapVar(
                $biddingScheme,
                SOAP_ENC_OBJECT,
                'TargetCpaBiddingScheme',
                'https://bingads.microsoft.com/CampaignManagement/v13'
            );
        }

        return $this;
    }

    /**
     * setBudget()
     *
     * @param int $amount
     *
     */
    public function setBudget($amount = 0){
        $this->request()->DailyBudget = $amount;
        return $this;
    }
}
