<?php namespace LaravelAds\Services\BingAds\Operations;

use LaravelAds\Services\BingAds\Operations\CampaignOperations;

use Microsoft\BingAds\Auth\ServiceClient;
use Microsoft\BingAds\Auth\ServiceClientType;

class Campaign extends CampaignOperations
{
    /**
     * getId()
     *
     */
    public function getId()
    {
        return $this->response()->Id ?? 0;
    }

    /**
     * setId()
     *
     * @param int $id
     *
     */
    public function setId($id)
    {
        $this->request()->Id = $id;

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
        return $this->response()->Name ?? '';
    }

    /**
     * setName()
     *
     * @param string $name
     *
     */
    public function setName($name)
    {
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
        $status = strtoupper($this->response()->Status??'UNKNOWN');

        switch($status) {
            case 'ACTIVE'                 : return 'ENABLED'; break;
            case 'BUDGETPAUSED'           : return 'ENABLED'; break;
            case 'BUDGETANDMANUALPAUSED'  : return 'PAUSED';  break;
            case 'PAUSED'                 : return 'PAUSED';  break;
            case 'REMOVED'                : return 'DELETED'; break;
            default :
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
        $type = strtoupper($this->response()->BudgetType??'UNKNOWN');

        switch($type) {
            case 'DAILYBUDGETACCELERATED' : return 'ACCELERATED'; break;
            case 'DAILYBUDGETSTANDARD'    : return 'STANDARD';  break;
            default :
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
        return strtoupper($this->response()->CampaignType??'UNKNOWN');
    }

    /**
     * getBidStrategy()
     *
     * @return string
     *
     */
    public function getBidStrategy()
    {
        $type = $this->response()->BiddingScheme->Type ?? 'UNKNOWN';

        switch($type)
        {
            case 'EnhancedCpc' : return 'ECPC'; break;
            case 'ManualCpc'   : return 'CPC'; break;
            case 'TargetCpa'   : return 'CPA'; break;
            default :
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
        if ($this->getBidStrategy() == 'CPA')
        {
            return $this->response()->BiddingScheme->TargetCpa??0;
        }

        return 0;
    }

    /**
     * setBudget()
     *
     * @param int $budget
     *
     */
    public function setBudget($amount = 0)
    {
        return $this;
    }

}
