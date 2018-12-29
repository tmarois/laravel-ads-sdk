<?php namespace LaravelAds\Services\GoogleAds\Operations;


use Google\AdsApi\AdWords\v201809\cm\AdGroup;
use Google\AdsApi\AdWords\v201809\cm\AdGroupOperation as ApiAdGroupOperation;
use Google\AdsApi\AdWords\v201809\cm\AdGroupService;
use Google\AdsApi\AdWords\v201809\cm\BiddingStrategyConfiguration;
use Google\AdsApi\AdWords\v201809\cm\CpcBid;
use Google\AdsApi\AdWords\v201809\cm\CpaBid;
use Google\AdsApi\AdWords\v201809\cm\CpmBid;
use Google\AdsApi\AdWords\v201809\cm\Money;
use Google\AdsApi\AdWords\v201809\cm\Operator;

class AdGroupOperation
{
    /**
     * $service
     *
     */
    protected $service = null;

    /**
     * $adGroupId
     *
     */
    protected $adGroupId = null;

    /**
     * $operations
     *
     */
    protected $operations = [];

    /**
     * $operations
     *
     */
    protected $adGroup = [];

    /**
     * __construct()
     *
     *
     */
    public function __construct($service, $adGroupId)
    {
        $this->service = $service;

        $this->adGroupId = $adGroupId;

        $this->operations = [];

        $this->adGroup = new AdGroup();
        $this->adGroup->setId($this->adGroupId);
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
    public function setBid($amount, $type = 'cpc')
    {
        switch($type)
        {
            case 'cpc' : $bid = new CpcBid(); break;
            case 'cpa' : $bid = new CpaBid(); break;
            case 'cpm' : $bid = new CpmBid(); break;
            default : return false;
        }

        $money = new Money();

        $money->setMicroAmount($amount*1000000);
        $bid->setBid($money);

        $biddingStrategyConfiguration = new BiddingStrategyConfiguration();
        $biddingStrategyConfiguration->setBids([$bid]);

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

    /**
     * save()
     *
     * Post your changes to Google Ads Server
     *
     *
     */
    public function save()
    {
        $operation = new ApiAdGroupOperation();
        $operation->setOperand($this->adGroup);
        $operation->setOperator(Operator::SET);

        $this->operations[] = $operation;

        return ($this->service->service(AdGroupService::class))->mutate($this->operations);
    }


}
