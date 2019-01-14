<?php namespace LaravelAds\Services\BingAds;

use SoapVar;
use SoapFault;
use Exception;

use LaravelAds\Services\BingAds\Operations\AdGroupResponse;

use Microsoft\BingAds\V12\CampaignManagement\AddAdExtensionsRequest;
use Microsoft\BingAds\V12\CampaignManagement\AddAdGroupCriterionsRequest;
use Microsoft\BingAds\V12\CampaignManagement\AddAdGroupsRequest;
use Microsoft\BingAds\V12\CampaignManagement\AddAdsRequest;
use Microsoft\BingAds\V12\CampaignManagement\AddAudiencesRequest;
use Microsoft\BingAds\V12\CampaignManagement\AddBudgetsRequest;
use Microsoft\BingAds\V12\CampaignManagement\AddCampaignCriterionsRequest;
use Microsoft\BingAds\V12\CampaignManagement\AddCampaignsRequest;
use Microsoft\BingAds\V12\CampaignManagement\AddConversionGoalsRequest;
use Microsoft\BingAds\V12\CampaignManagement\AddExperimentsRequest;
use Microsoft\BingAds\V12\CampaignManagement\AddKeywordsRequest;
use Microsoft\BingAds\V12\CampaignManagement\AddLabelsRequest;
use Microsoft\BingAds\V12\CampaignManagement\AddListItemsToSharedListRequest;
use Microsoft\BingAds\V12\CampaignManagement\AddMediaRequest;
use Microsoft\BingAds\V12\CampaignManagement\AddNegativeKeywordsToEntitiesRequest;
use Microsoft\BingAds\V12\CampaignManagement\AddSharedEntityRequest;
use Microsoft\BingAds\V12\CampaignManagement\AddUetTagsRequest;
use Microsoft\BingAds\V12\CampaignManagement\AppealEditorialRejectionsRequest;
use Microsoft\BingAds\V12\CampaignManagement\ApplyOfflineConversionsRequest;
use Microsoft\BingAds\V12\CampaignManagement\ApplyProductPartitionActionsRequest;
use Microsoft\BingAds\V12\CampaignManagement\DeleteAdExtensionsRequest;
use Microsoft\BingAds\V12\CampaignManagement\DeleteAdExtensionsAssociationsRequest;
use Microsoft\BingAds\V12\CampaignManagement\DeleteAdGroupCriterionsRequest;
use Microsoft\BingAds\V12\CampaignManagement\DeleteAdGroupsRequest;
use Microsoft\BingAds\V12\CampaignManagement\DeleteAdsRequest;
use Microsoft\BingAds\V12\CampaignManagement\DeleteAudiencesRequest;
use Microsoft\BingAds\V12\CampaignManagement\DeleteBudgetsRequest;
use Microsoft\BingAds\V12\CampaignManagement\DeleteCampaignCriterionsRequest;
use Microsoft\BingAds\V12\CampaignManagement\DeleteCampaignsRequest;
use Microsoft\BingAds\V12\CampaignManagement\DeleteExperimentsRequest;
use Microsoft\BingAds\V12\CampaignManagement\DeleteKeywordsRequest;
use Microsoft\BingAds\V12\CampaignManagement\DeleteLabelAssociationsRequest;
use Microsoft\BingAds\V12\CampaignManagement\DeleteLabelsRequest;
use Microsoft\BingAds\V12\CampaignManagement\DeleteListItemsFromSharedListRequest;
use Microsoft\BingAds\V12\CampaignManagement\DeleteMediaRequest;
use Microsoft\BingAds\V12\CampaignManagement\DeleteNegativeKeywordsFromEntitiesRequest;
use Microsoft\BingAds\V12\CampaignManagement\DeleteSharedEntitiesRequest;
use Microsoft\BingAds\V12\CampaignManagement\DeleteSharedEntityAssociationsRequest;
use Microsoft\BingAds\V12\CampaignManagement\GetAccountMigrationStatusesRequest;
use Microsoft\BingAds\V12\CampaignManagement\GetAccountPropertiesRequest;
use Microsoft\BingAds\V12\CampaignManagement\GetAdExtensionIdsByAccountIdRequest;
use Microsoft\BingAds\V12\CampaignManagement\GetAdExtensionsAssociationsRequest;
use Microsoft\BingAds\V12\CampaignManagement\GetAdExtensionsByIdsRequest;
use Microsoft\BingAds\V12\CampaignManagement\GetAdExtensionsEditorialReasonsRequest;
use Microsoft\BingAds\V12\CampaignManagement\GetAdGroupCriterionsByIdsRequest;
use Microsoft\BingAds\V12\CampaignManagement\GetAdGroupsByCampaignIdRequest;
use Microsoft\BingAds\V12\CampaignManagement\GetAdGroupsByIdsRequest;
use Microsoft\BingAds\V12\CampaignManagement\GetAdsByAdGroupIdRequest;
use Microsoft\BingAds\V12\CampaignManagement\GetAdsByEditorialStatusRequest;
use Microsoft\BingAds\V12\CampaignManagement\GetAdsByIdsRequest;
use Microsoft\BingAds\V12\CampaignManagement\GetAudiencesByIdsRequest;
use Microsoft\BingAds\V12\CampaignManagement\GetBMCStoresByCustomerIdRequest;
use Microsoft\BingAds\V12\CampaignManagement\GetBSCCountriesRequest;
use Microsoft\BingAds\V12\CampaignManagement\GetBudgetsByIdsRequest;
use Microsoft\BingAds\V12\CampaignManagement\GetCampaignCriterionsByIdsRequest;
use Microsoft\BingAds\V12\CampaignManagement\GetCampaignIdsByBudgetIdsRequest;
use Microsoft\BingAds\V12\CampaignManagement\GetCampaignsByAccountIdRequest;
use Microsoft\BingAds\V12\CampaignManagement\GetCampaignsByIdsRequest;
use Microsoft\BingAds\V12\CampaignManagement\GetConversionGoalsByIdsRequest;
use Microsoft\BingAds\V12\CampaignManagement\GetConversionGoalsByTagIdsRequest;
use Microsoft\BingAds\V12\CampaignManagement\GetEditorialReasonsByIdsRequest;
use Microsoft\BingAds\V12\CampaignManagement\GetExperimentsByIdsRequest;
use Microsoft\BingAds\V12\CampaignManagement\GetGeoLocationsFileUrlRequest;
use Microsoft\BingAds\V12\CampaignManagement\GetKeywordsByAdGroupIdRequest;
use Microsoft\BingAds\V12\CampaignManagement\GetKeywordsByEditorialStatusRequest;
use Microsoft\BingAds\V12\CampaignManagement\GetKeywordsByIdsRequest;
use Microsoft\BingAds\V12\CampaignManagement\GetLabelAssociationsByEntityIdsRequest;
use Microsoft\BingAds\V12\CampaignManagement\GetLabelAssociationsByLabelIdsRequest;
use Microsoft\BingAds\V12\CampaignManagement\GetLabelsByIdsRequest;
use Microsoft\BingAds\V12\CampaignManagement\GetListItemsBySharedListRequest;
use Microsoft\BingAds\V12\CampaignManagement\GetMediaAssociationsRequest;
use Microsoft\BingAds\V12\CampaignManagement\GetMediaMetaDataByAccountIdRequest;
use Microsoft\BingAds\V12\CampaignManagement\GetMediaMetaDataByIdsRequest;
use Microsoft\BingAds\V12\CampaignManagement\GetNegativeKeywordsByEntityIdsRequest;
use Microsoft\BingAds\V12\CampaignManagement\GetNegativeSitesByAdGroupIdsRequest;
use Microsoft\BingAds\V12\CampaignManagement\GetNegativeSitesByCampaignIdsRequest;
use Microsoft\BingAds\V12\CampaignManagement\GetProfileDataFileUrlRequest;
use Microsoft\BingAds\V12\CampaignManagement\GetSharedEntitiesByAccountIdRequest;
use Microsoft\BingAds\V12\CampaignManagement\GetSharedEntityAssociationsByEntityIdsRequest;
use Microsoft\BingAds\V12\CampaignManagement\GetSharedEntityAssociationsBySharedEntityIdsRequest;
use Microsoft\BingAds\V12\CampaignManagement\GetUetTagsByIdsRequest;
use Microsoft\BingAds\V12\CampaignManagement\SetAccountPropertiesRequest;
use Microsoft\BingAds\V12\CampaignManagement\SetAdExtensionsAssociationsRequest;
use Microsoft\BingAds\V12\CampaignManagement\SetLabelAssociationsRequest;
use Microsoft\BingAds\V12\CampaignManagement\SetNegativeSitesToAdGroupsRequest;
use Microsoft\BingAds\V12\CampaignManagement\SetNegativeSitesToCampaignsRequest;
use Microsoft\BingAds\V12\CampaignManagement\SetSharedEntityAssociationsRequest;
use Microsoft\BingAds\V12\CampaignManagement\UpdateAdExtensionsRequest;
use Microsoft\BingAds\V12\CampaignManagement\UpdateAdGroupCriterionsRequest;
use Microsoft\BingAds\V12\CampaignManagement\UpdateAdGroupsRequest;
use Microsoft\BingAds\V12\CampaignManagement\UpdateAdsRequest;
use Microsoft\BingAds\V12\CampaignManagement\UpdateAudiencesRequest;
use Microsoft\BingAds\V12\CampaignManagement\UpdateBudgetsRequest;
use Microsoft\BingAds\V12\CampaignManagement\UpdateCampaignCriterionsRequest;
use Microsoft\BingAds\V12\CampaignManagement\UpdateCampaignsRequest;
use Microsoft\BingAds\V12\CampaignManagement\UpdateConversionGoalsRequest;
use Microsoft\BingAds\V12\CampaignManagement\UpdateExperimentsRequest;
use Microsoft\BingAds\V12\CampaignManagement\UpdateKeywordsRequest;
use Microsoft\BingAds\V12\CampaignManagement\UpdateLabelsRequest;
use Microsoft\BingAds\V12\CampaignManagement\UpdateSharedEntitiesRequest;
use Microsoft\BingAds\V12\CampaignManagement\UpdateUetTagsRequest;

use Microsoft\BingAds\Auth\ServiceClientType;

class Fetch
{
    /**
     * $service
     *
     */
    protected $service = null;

    /**
     * __construct()
     *
     *
     */
    public function __construct($service)
    {
        $this->service = $service;
    }

    /**
     * getCampaigns()
     *
     *
     * @return object Collection
     */
    public function getCampaigns()
    {
        $proxy = $this->service->serviceProxy(ServiceClientType::CampaignManagementVersion12);
        $proxy->SetAuthorizationData($this->service->session());

        $request = new GetCampaignsByAccountIdRequest();
        $request->AccountId = $this->service->getClientId();

        $r = [];

        try
        {
            $items = $proxy->GetService()->GetCampaignsByAccountId($request);
        }
        catch(\Exception $e) {
            return [];
        }

        foreach ($items->Campaigns->Campaign as $item)
        {
            $r[] = [
                'id' => $item->Id,
                'name' => $item->Name,
                'status' => $item->Status,
                'channel' => $item->CampaignType,
                'budget' => $item->DailyBudget,
                'bid_type' => $item->BiddingScheme->Type ?? 'Unknown'
            ];
        }


        return collect($r);

        // print $proxy->GetService()->__getLastRequest()."\n";
        // print $proxy->GetService()->__getLastResponse()."\n";
    }

    /**
     * getAdGroups()
     *
     *
     * @return object Collection
     */
    public function getAdGroups()
    {
        $campaigns = $this->getCampaigns();

        $r = [];
        foreach($campaigns->all() as $campaign)
        {
            $proxy = $this->service->serviceProxy(ServiceClientType::CampaignManagementVersion12);
            $proxy->SetAuthorizationData($this->service->session());

            $request = new GetAdGroupsByCampaignIdRequest();
            $request->CampaignId = $campaign['id'];

            try
            {
                $items = $proxy->GetService()->GetAdGroupsByCampaignId($request);
            }
            catch(\Exception $e) {
                return [];
            }

            foreach($items->AdGroups->AdGroup as $item)
            {
                $adgroup = (new AdGroupResponse($item));

                $r[] = [
                    'id' => $adgroup->getId(),
                    'campaign_id' => $adgroup->getCampaignId(),
                    'name' => $adgroup->getName(),
                    'status' => $adgroup->getStatus(),
                    'bid' => $adgroup->getBid(),
                    'bid_type' => $adgroup->getBidType()
                ];
            }
        }

        return collect($r);
    }

}


// GetCampaignsByAccountId
