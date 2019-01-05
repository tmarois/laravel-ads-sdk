<?php namespace LaravelAds\Services\BingAds\Operations;


use SoapVar;

use LaravelAds\Services\BingAds\Operations\AdGroupResponse;

use Microsoft\BingAds\V12\CampaignManagement\GetAdGroupsByIdsRequest;
use Microsoft\BingAds\V12\CampaignManagement\UpdateAdGroupsRequest;
use Microsoft\BingAds\V12\CampaignManagement\AdGroup;
use Microsoft\BingAds\V12\CampaignManagement\AdGroupStatus;

use Microsoft\BingAds\V12\CampaignManagement\Bid;
use Microsoft\BingAds\V12\CampaignManagement\BiddingScheme;
use Microsoft\BingAds\V12\CampaignManagement\EnhancedCpcBiddingScheme;
use Microsoft\BingAds\V12\CampaignManagement\InheritFromParentBiddingScheme;
use Microsoft\BingAds\V12\CampaignManagement\ManualCpcBiddingScheme;

use Microsoft\BingAds\Auth\ServiceClient;
use Microsoft\BingAds\Auth\ServiceClientType;

class AdGroupRequest
{
    /**
     * $service
     *
     */
    protected $service = null;

    /**
     * $proxy
     *
     */
    protected $proxy = null;

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
    public function __construct($service)
    {
        // set the service object
        $this->service = $service;

        // set the proxy for access
        $this->proxy = $this->service->serviceProxy(ServiceClientType::CampaignManagementVersion12);
        $this->proxy->SetAuthorizationData($this->service->session());

        // create the ad group object
        $this->adGroup = new AdGroup();
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
     * setBiddingType()
     *
     * @param string $scheme
     *
     */
    public function setBiddingType($scheme)
    {
        // $biddingScheme = new InheritFromParentBiddingScheme();
        // $this->adGroup->BiddingScheme = new SoapVar($biddingScheme,SOAP_ENC_OBJECT,'InheritFromParentBiddingScheme',$this->proxy->GetNamespace());

        // $this->adGroup->BiddingScheme = new InheritFromParentBiddingScheme();
        // $this->adGroup->BiddingScheme->InheritedBidStrategyType = $scheme;

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
        $this->adGroup->CampaignId = $id;

        return $this;
    }

    /**
     * setBid()
     * https://github.com/BingAds/BingAds-PHP-SDK/blob/dc5c8fb9f9390ab14c102fdff68cb7592091b55c/samples/V12/KeywordsAds.php#L126
     *
     * @param float $amount
     *
     */
    public function setBid($amount)
    {
        $this->adGroup->CpcBid = new Bid();
        $this->adGroup->CpcBid->Amount = $amount;

        return $this;
    }

    /**
     * setName()
     *
     * @param string $name
     *
     */
    public function setName($name)
    {
        $this->adGroup->Name = $name;

        return $this;
    }

    /**
     * setId()
     *
     * @param int $id
     *
     */
    public function setId($id)
    {
        $this->adGroup->Id = $id;

        return $this;
    }

    /**
     * get()
     *
     */
    public function get()
    {
        try
        {
            $request = new GetAdGroupsByIdsRequest();
            $request->CampaignId = $this->adGroup->CampaignId;
            $request->AdGroupIds = [$this->adGroup->Id];

            return (new AdGroupResponse($this->proxy->GetService()->GetAdGroupsByIds($request)->AdGroups->AdGroup[0]));
        }
        catch(\Exception $e) {
            print $this->proxy->GetService()->__getLastRequest()."\n";
            print $this->proxy->GetService()->__getLastResponse()."\n";
        }
    }

    /**
     * save()
     *
     */
    public function save()
    {
        try
        {
            $request = new UpdateAdGroupsRequest();
            $request->CampaignId = $this->adGroup->CampaignId;
            $request->AdGroups = [$this->adGroup];
            $request->UpdateAudienceAdsBidAdjustment = true;
            $request->ReturnInheritedBidStrategyTypes = true;

            return $this->proxy->GetService()->UpdateAdGroups($request);
        }
        catch(\Exception $e) {
            print $this->proxy->GetService()->__getLastRequest()."\n";
            print $this->proxy->GetService()->__getLastResponse()."\n";
        }
    }
}
