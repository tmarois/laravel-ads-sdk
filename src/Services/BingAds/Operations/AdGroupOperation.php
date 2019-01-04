<?php namespace LaravelAds\Services\BingAds\Operations;


use Microsoft\BingAds\V12\CampaignManagement\GetAdGroupsByIdsRequest;
use Microsoft\BingAds\V12\CampaignManagement\UpdateAdGroupsRequest;
use Microsoft\BingAds\V12\CampaignManagement\AdGroup;
use Microsoft\BingAds\V12\CampaignManagement\AdGroupStatus;
use Microsoft\BingAds\Auth\ServiceClient;
use Microsoft\BingAds\Auth\ServiceClientType;

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
     * $campaignId
     *
     */
    protected $campaignId = null;

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
    public function __construct($service, $adGroupId = null, $campaignId = null)
    {
        $this->service = $service;

        $this->adGroup = new AdGroup();

        if ($adGroupId && $campaignId)
        {
            $this->adGroupId  = $adGroupId;

            $this->campaignId = $campaignId;

            $this->adGroup = $this->get();
        }
    }

    /**
     * setStatus()
     * https://github.com/BingAds/BingAds-PHP-SDK/blob/b4fcc4a849a3927c04828804b3acfa7921cdcab9/src/V12/CampaignManagement/AdGroupStatus.php
     *
     * @param string $status
     *
     */
    public function setStatus($status)
    {
        $status = strtoupper($status);

        switch($status)
        {
            case 'ACTIVE' : case 'ENABLED' : $this->adGroup->Status = AdGroupStatus::Active; break;
            case 'PAUSED' : $this->adGroup->Status = AdGroupStatus::Paused; break;
            default :
        }

        return $this;
    }

    /**
     * getBid()
     *
     */
    public function getBid()
    {
        return $this->adGroup->CpcBid->Amount;
    }

    /**
     * getStatus()
     *
     * @return string
     *
     */
    public function getStatus()
    {
        return $this->adGroup->Status;
    }

    /**
     * getId()
     *
     * @return int
     *
     */
    public function getId()
    {
        return $this->adGroup->Id;
    }

    /**
     * getName()
     *
     * @return string
     *
     */
    public function getName()
    {
        return $this->adGroup->Name;
    }

    /**
     * getBidType()
     *
     */
    public function getBidType()
    {
        return $this->adGroup->BiddingScheme->InheritedBidStrategyType;
    }

    /**
     * get()
     *
     */
    public function get()
    {
        $proxy = $this->service->serviceProxy(ServiceClientType::CampaignManagementVersion12);
        $proxy->SetAuthorizationData($this->service->session());

        try {

            $request = new GetAdGroupsByIdsRequest();
            $request->CampaignId = $this->campaignId;
            $request->AdGroupIds = [$this->adGroupId];

            return $proxy->GetService()->GetAdGroupsByIds($request)->AdGroups->AdGroup[0];
        }
        catch(\Exception $e) {
            print $proxy->GetService()->__getLastRequest()."\n";
            print $proxy->GetService()->__getLastResponse()."\n";
        }
    }

    /**
     * save()
     *
     */
    public function save()
    {
        $proxy = $this->service->serviceProxy(ServiceClientType::CampaignManagementVersion12);
        $proxy->SetAuthorizationData($this->service->session());

        try {

            $request = new UpdateAdGroupsRequest();
            $request->CampaignId = $this->campaignId;
            $request->AdGroups = [$this->adGroup];
            $request->UpdateAudienceAdsBidAdjustment = true;
            $request->ReturnInheritedBidStrategyTypes = true;

            return $proxy->GetService()->UpdateAdGroups($request);
        }
        catch(\Exception $e) {
            print $proxy->GetService()->__getLastRequest()."\n";
            print $proxy->GetService()->__getLastResponse()."\n";
        }


    }
}
