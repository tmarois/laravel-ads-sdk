<?php namespace LaravelAds\Services\GoogleAds\Operations;


use LaravelAds\Services\GoogleAds\Operations\AdGroupResponse;

use Google\AdsApi\AdWords\v201809\cm\AdGroup;
use Google\AdsApi\AdWords\v201809\cm\AdGroupOperation as ApiAdGroupOperation;
use Google\AdsApi\AdWords\v201809\cm\AdGroupService;
use Google\AdsApi\AdWords\v201809\cm\BiddingStrategyConfiguration;
use Google\AdsApi\AdWords\v201809\cm\CpcBid;
use Google\AdsApi\AdWords\v201809\cm\CpaBid;
use Google\AdsApi\AdWords\v201809\cm\CpmBid;
use Google\AdsApi\AdWords\v201809\cm\Money;
use Google\AdsApi\AdWords\v201809\cm\Operator;

class AdGroupRequest
{
    /**
     * $service
     *
     */
    protected $service = null;

    /**
     * $operations
     *
     */
    protected $adGroup = null;

    /**
     * __construct()
     *
     */
    public function __construct($service)
    {
        $this->service = $service;

        $this->adGroup = new AdGroup();
    }

    /**
     * setId()
     *
     * @param int $id
     *
     */
    public function setId($id)
    {
        $this->adGroup->setId($id);

        return $this;
    }

    /**
     * setCampaignId()
     *
     * @param int $id
     *
     */
    public function setCampaignId($id)
    {
        $this->adGroup->setCampaignId($id);

        return $this;
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
        /*switch($this->getBidType())
        {
            case 'MANUAL_CPC' :
                $bid = new CpcBid();
            break;
            case 'TARGET_CPA' :
                $bid = new CpaBid();
            break;
            case 'MANUAL_CPM' :
                $bid = new CpmBid();
            break;
            default : return false;
        }*/

        $bid   = new CpcBid();

        $money = new Money();

        $money->setMicroAmount($amount*1000000);
        $bid->setBid($money);

        $biddingStrategyConfiguration = new BiddingStrategyConfiguration();
        $biddingStrategyConfiguration->setBids([$bid]);

        // can not set this on ad group level, this is set on campaign level...
        // $biddingStrategyConfiguration->setBiddingStrategyType($bidType);

        $this->adGroup->setBiddingStrategyConfiguration($biddingStrategyConfiguration);

        return $this;
    }

    /**
     * setName()
     *
     * @reference
     * https://github.com/googleads/googleads-php-lib/blob/master/src/Google/AdsApi/AdWords/v201809/cm/AdGroup.php
     *
     * @param string $name
     *
     */
    public function setName($name)
    {
        $this->adGroup->setName($name);

        return $this;
    }

    /**
     * setStatus()
     *
     * @reference
     * https://github.com/googleads/googleads-php-lib/blob/master/src/Google/AdsApi/AdWords/v201809/cm/AdGroup.php
     *
     * @param string $status
     *
     */
    public function setStatus($status)
    {
        if (in_array($status, ['ENABLED','PAUSED'])) {
            $this->adGroup->setStatus($status);
        }

        return $this;
    }


    /**
     * get()
     *
     */
    public function get()
    {
        $operation = new ApiAdGroupOperation();
        $operation->setOperand($this->adGroup);
        $operation->setOperator(Operator::SET);

        $adgroup = ($this->service->service(AdGroupService::class))->mutate([$operation]);

        return (new AdGroupResponse($adgroup->getValue()[0] ?? null));
    }

    /**
     * save()
     *
     * Post your changes to Google Ads Server
     *
     *
     */
    public function save()
    {
        return $this->get();
    }


}
