<?php

namespace LaravelAds\Services\BingAds;

use SoapVar;
use Exception;
use SoapFault;
use ZipArchive;


use Microsoft\BingAds\Auth\ServiceClientType;
use Microsoft\BingAds\V13\Reporting\AccountPerformanceReportColumn;
use Microsoft\BingAds\V13\Reporting\AccountPerformanceReportRequest;
use Microsoft\BingAds\V13\Reporting\AccountReportScope;
use Microsoft\BingAds\V13\Reporting\AccountThroughAdGroupReportScope;
use Microsoft\BingAds\V13\Reporting\AdGroupPerformanceReportColumn;
use Microsoft\BingAds\V13\Reporting\AdGroupPerformanceReportRequest;
use Microsoft\BingAds\V13\Reporting\AdGroupReportScope;
use Microsoft\BingAds\V13\Reporting\AgeGenderAudienceReportColumn;
use Microsoft\BingAds\V13\Reporting\AgeGenderAudienceReportRequest;
use Microsoft\BingAds\V13\Reporting\CampaignPerformanceReportColumn;
use Microsoft\BingAds\V13\Reporting\CampaignPerformanceReportRequest;
use Microsoft\BingAds\V13\Reporting\CampaignReportScope;
use Microsoft\BingAds\V13\Reporting\Date;
use Microsoft\BingAds\V13\Reporting\DestinationUrlPerformanceReportColumn;
use Microsoft\BingAds\V13\Reporting\DestinationUrlPerformanceReportRequest;
use Microsoft\BingAds\V13\Reporting\GeographicPerformanceReportColumn;
use Microsoft\BingAds\V13\Reporting\GeographicPerformanceReportRequest;
use Microsoft\BingAds\V13\Reporting\KeywordPerformanceReportSort;
use Microsoft\BingAds\V13\Reporting\ReportAggregation;
use Microsoft\BingAds\V13\Reporting\ReportFormat;
use Microsoft\BingAds\V13\Reporting\ReportRequestStatusType;
use Microsoft\BingAds\V13\Reporting\ReportTime;
use Microsoft\BingAds\V13\Reporting\SearchQueryPerformanceReportColumn;
use Microsoft\BingAds\V13\Reporting\SearchQueryPerformanceReportRequest;
use Microsoft\BingAds\V13\Reporting\KeywordPerformanceReportColumn;
use Microsoft\BingAds\V13\Reporting\KeywordPerformanceReportRequest;
use Microsoft\BingAds\V13\Reporting\SortOrder;
use Microsoft\BingAds\V13\Reporting\SubmitGenerateReportRequest;

class Reports
{
    /**
     * $service
     *
     */
    protected $service = null;

    /**
     * $serviceProxy
     *
     */
    protected $serviceProxy = null;

    /**
     * $dateFrom
     *
     */
    protected $dateRange = [];

    /**
     * $fields
     *
     */
    protected $fields = [];

    /**
     * __construct()
     *
     *
     */
    public function __construct($service)
    {
        $this->service = $service;

        $this->serviceProxy = $this->service->call(ServiceClientType::ReportingVersion13);
    }

    /**
     * setDateRange()
     *
     *
     * @return self
     */
    public function setDateRange($dateFrom, $dateTo)
    {
        $this->dateRange[] = $dateFrom;
        $this->dateRange[] = $dateTo;

        return $this;
    }

    /**
     * setFields()
     *
     *
     * @return self
     */
    public function setFields($fields, $auto = false)
    {
        if ($auto == false) {
            $this->fields = $fields;
        }

        if ($auto == true && empty($this->fields)) {
            $this->fields = $fields;
        }

        return $this;
    }


    protected function submitGenerateReport($report)
    {
        $request = new SubmitGenerateReportRequest();
        $request->ReportRequest = $report;

        return $this->serviceProxy->GetService()->SubmitGenerateReport($request);
    }


    /**
     * buildAccountReport()
     *
     *
     */
    public function buildAccountReport($aggregation = ReportAggregation::Daily)
    {
        $report                         = new AccountPerformanceReportRequest();
        $report->ReportName             = 'Account Performance Report';
        $report->Format                 = ReportFormat::Csv;
        $report->ReturnOnlyCompleteData = false;
        $report->Aggregation            = $aggregation;

        $report->Scope                  = new AccountReportScope();
        $report->Scope->AccountIds      = [$this->service->getClientId()];

        $report->Time                               = new ReportTime();
        $report->Time->CustomDateRangeStart         = new Date();
        $report->Time->CustomDateRangeStart->Day    = date('d', strtotime($this->dateRange[0]));
        $report->Time->CustomDateRangeStart->Month  = date('m', strtotime($this->dateRange[0]));
        $report->Time->CustomDateRangeStart->Year   = date('Y', strtotime($this->dateRange[0]));

        $report->Time->CustomDateRangeEnd           = new Date();
        $report->Time->CustomDateRangeEnd->Day      = date('d', strtotime($this->dateRange[1]));
        $report->Time->CustomDateRangeEnd->Month    = date('m', strtotime($this->dateRange[1]));
        $report->Time->CustomDateRangeEnd->Year     = date('Y', strtotime($this->dateRange[1]));

        if (!empty($this->fields)) {
            $report->Columns = $this->fields;
        } else {
            $report->Columns = [
                AccountPerformanceReportColumn::TimePeriod,
                AccountPerformanceReportColumn::AccountId,
                AccountPerformanceReportColumn::Clicks,
                AccountPerformanceReportColumn::Impressions,
                AccountPerformanceReportColumn::Spend,
                AccountPerformanceReportColumn::Conversions,
                AccountPerformanceReportColumn::Revenue
            ];
        }

        $encodedReport   = new SoapVar($report, SOAP_ENC_OBJECT, 'AccountPerformanceReportRequest', $this->serviceProxy->GetNamespace());
        $reportRequestId = $this->submitGenerateReport($encodedReport)->ReportRequestId;

        return (new ReportDownload($this->serviceProxy, $reportRequestId));
    }


    /**
     * buildCampaignReport()
     *
     *
     */
    public function buildCampaignReport($aggregation = ReportAggregation::Daily)
    {
        $report                         = new CampaignPerformanceReportRequest();
        $report->ReportName             = 'Campaign Performance Report';
        $report->Format                 = ReportFormat::Csv;
        $report->ReturnOnlyCompleteData = false;
        $report->Aggregation            = $aggregation;

        $report->Scope                  = new CampaignReportScope();
        $report->Scope->AccountIds      = [$this->service->getClientId()];

        $report->Time                               = new ReportTime();
        $report->Time->CustomDateRangeStart         = new Date();
        $report->Time->CustomDateRangeStart->Day    = date('d', strtotime($this->dateRange[0]));
        $report->Time->CustomDateRangeStart->Month  = date('m', strtotime($this->dateRange[0]));
        $report->Time->CustomDateRangeStart->Year   = date('Y', strtotime($this->dateRange[0]));

        $report->Time->CustomDateRangeEnd           = new Date();
        $report->Time->CustomDateRangeEnd->Day      = date('d', strtotime($this->dateRange[1]));
        $report->Time->CustomDateRangeEnd->Month    = date('m', strtotime($this->dateRange[1]));
        $report->Time->CustomDateRangeEnd->Year     = date('Y', strtotime($this->dateRange[1]));

        if (!empty($this->fields)) {
            $report->Columns = $this->fields;
        } else {
            $report->Columns = [
                CampaignPerformanceReportColumn::TimePeriod,
                CampaignPerformanceReportColumn::AccountId,
                CampaignPerformanceReportColumn::CampaignName,
                CampaignPerformanceReportColumn::CampaignId,
                CampaignPerformanceReportColumn::Clicks,
                CampaignPerformanceReportColumn::Impressions,
                CampaignPerformanceReportColumn::Spend,
                CampaignPerformanceReportColumn::Conversions,
                CampaignPerformanceReportColumn::Revenue
            ];
        }

        $encodedReport   = new SoapVar($report, SOAP_ENC_OBJECT, 'CampaignPerformanceReportRequest', $this->serviceProxy->GetNamespace());
        $reportRequestId = $this->submitGenerateReport($encodedReport)->ReportRequestId;

        return (new ReportDownload($this->serviceProxy, $reportRequestId));
    }


    /**
     * buildAdGroupReport()
     *
     *
     */
    public function buildAdGroupReport()
    {
        $report                         = new AdGroupPerformanceReportRequest();
        $report->ReportName             = 'Ad Group Performance Report';
        $report->Format                 = ReportFormat::Csv;
        $report->ReturnOnlyCompleteData = false;
        $report->Aggregation            = ReportAggregation::Daily;

        $report->Scope                  = new AdGroupReportScope();
        $report->Scope->AccountIds      = [$this->service->getClientId()];

        $report->Time                               = new ReportTime();
        $report->Time->CustomDateRangeStart         = new Date();
        $report->Time->CustomDateRangeStart->Day    = date('d', strtotime($this->dateRange[0]));
        $report->Time->CustomDateRangeStart->Month  = date('m', strtotime($this->dateRange[0]));
        $report->Time->CustomDateRangeStart->Year   = date('Y', strtotime($this->dateRange[0]));

        $report->Time->CustomDateRangeEnd           = new Date();
        $report->Time->CustomDateRangeEnd->Day      = date('d', strtotime($this->dateRange[1]));
        $report->Time->CustomDateRangeEnd->Month    = date('m', strtotime($this->dateRange[1]));
        $report->Time->CustomDateRangeEnd->Year     = date('Y', strtotime($this->dateRange[1]));

        if (!empty($this->fields)) {
            $report->Columns = $this->fields;
        } else {
            $report->Columns = [
                AdGroupPerformanceReportColumn::TimePeriod,
                AdGroupPerformanceReportColumn::AccountId,
                AdGroupPerformanceReportColumn::CampaignId,
                AdGroupPerformanceReportColumn::CampaignName,
                AdGroupPerformanceReportColumn::AdGroupId,
                AdGroupPerformanceReportColumn::AdGroupName,
                AdGroupPerformanceReportColumn::Clicks,
                AdGroupPerformanceReportColumn::Impressions,
                AdGroupPerformanceReportColumn::Spend,
                AdGroupPerformanceReportColumn::Conversions,
                AdGroupPerformanceReportColumn::Revenue,
                AdGroupPerformanceReportColumn::AveragePosition
            ];
        }

        $encodedReport   = new SoapVar($report, SOAP_ENC_OBJECT, 'AdGroupPerformanceReportRequest', $this->serviceProxy->GetNamespace());
        $reportRequestId = $this->submitGenerateReport($encodedReport)->ReportRequestId;

        return (new ReportDownload($this->serviceProxy, $reportRequestId));
    }


    /**
     * buildFinalUrlReport()
     *
     *
     */
    public function buildFinalUrlReport()
    {
        $reportRequestId = null;

        try {
            $report                         = new DestinationUrlPerformanceReportRequest();
            $report->ReportName             = 'Destination Url Performance Report';
            $report->Format                 = ReportFormat::Csv;
            $report->ReturnOnlyCompleteData = false;
            $report->Aggregation            = ReportAggregation::Daily;

            $report->Scope                  = new AccountThroughAdGroupReportScope();
            $report->Scope->AccountIds      = [$this->service->getClientId()];

            $report->Time                               = new ReportTime();
            $report->Time->CustomDateRangeStart         = new Date();
            $report->Time->CustomDateRangeStart->Day    = date('d', strtotime($this->dateRange[0]));
            $report->Time->CustomDateRangeStart->Month  = date('m', strtotime($this->dateRange[0]));
            $report->Time->CustomDateRangeStart->Year   = date('Y', strtotime($this->dateRange[0]));

            $report->Time->CustomDateRangeEnd           = new Date();
            $report->Time->CustomDateRangeEnd->Day      = date('d', strtotime($this->dateRange[1]));
            $report->Time->CustomDateRangeEnd->Month    = date('m', strtotime($this->dateRange[1]));
            $report->Time->CustomDateRangeEnd->Year     = date('Y', strtotime($this->dateRange[1]));

            if (!empty($this->fields)) {
                $report->Columns = $this->fields;
            } else {
                $report->Columns = [
                    DestinationUrlPerformanceReportColumn::TimePeriod,
                    DestinationUrlPerformanceReportColumn::AccountName,
                    DestinationUrlPerformanceReportColumn::AccountId,
                    DestinationUrlPerformanceReportColumn::CampaignId,
                    DestinationUrlPerformanceReportColumn::CampaignName,
                    DestinationUrlPerformanceReportColumn::Clicks,
                    DestinationUrlPerformanceReportColumn::Impressions,
                    DestinationUrlPerformanceReportColumn::Spend,
                    DestinationUrlPerformanceReportColumn::Conversions,
                    DestinationUrlPerformanceReportColumn::Revenue,
                    DestinationUrlPerformanceReportColumn::DestinationUrl,
                    DestinationUrlPerformanceReportColumn::FinalUrl
                ];
            }

            $encodedReport   = new SoapVar($report, SOAP_ENC_OBJECT, 'DestinationUrlPerformanceReportRequest', $this->serviceProxy->GetNamespace());

            $reportRequest = $this->submitGenerateReport($encodedReport);

            if ($reportRequest) {
                $reportRequestId = $reportRequest->ReportRequestId;
            }
        } catch (SoapFault $e) {
            printf("-----\r\nFault Code: %s\r\nFault String: %s\r\nFault Detail: \r\n", $e->faultcode, $e->faultstring);

            if (isset($e->detail)) {
                var_dump($e->detail);
            }

            // print "-----\r\nLast SOAP request/response:\r\n";
            // print $this->serviceProxy->GetWsdl() . "\r\n";
            // print $this->serviceProxy->__getLastRequest()."\r\n";
            // print $this->serviceProxy->__getLastResponse()."\r\n";
        }

        return (new ReportDownload($this->serviceProxy, $reportRequestId));
    }


    /**
     * buildSearchTermReport()
     *
     *
     */
    public function buildSearchTermReport($aggregation = ReportAggregation::Summary)
    {
        $reportRequestId = null;

        try {
            $report                         = new SearchQueryPerformanceReportRequest();
            $report->ReportName             = 'Search Query Performance Report';
            $report->Format                 = ReportFormat::Csv;
            $report->ReturnOnlyCompleteData = false;
            $report->Aggregation            = $aggregation;

            $report->Scope                  = new AccountThroughAdGroupReportScope();
            $report->Scope->AccountIds      = [$this->service->getClientId()];

            $report->Time                               = new ReportTime();
            $report->Time->CustomDateRangeStart         = new Date();
            $report->Time->CustomDateRangeStart->Day    = date('d', strtotime($this->dateRange[0]));
            $report->Time->CustomDateRangeStart->Month  = date('m', strtotime($this->dateRange[0]));
            $report->Time->CustomDateRangeStart->Year   = date('Y', strtotime($this->dateRange[0]));

            $report->Time->CustomDateRangeEnd           = new Date();
            $report->Time->CustomDateRangeEnd->Day      = date('d', strtotime($this->dateRange[1]));
            $report->Time->CustomDateRangeEnd->Month    = date('m', strtotime($this->dateRange[1]));
            $report->Time->CustomDateRangeEnd->Year     = date('Y', strtotime($this->dateRange[1]));

            if (!empty($this->fields)) {
                $report->Columns = $this->fields;
            } else {
                $report->Columns = [
                    SearchQueryPerformanceReportColumn::Clicks,
                    SearchQueryPerformanceReportColumn::Impressions,
                    SearchQueryPerformanceReportColumn::Spend,
                    SearchQueryPerformanceReportColumn::Conversions,
                    SearchQueryPerformanceReportColumn::Revenue,
                    SearchQueryPerformanceReportColumn::SearchQuery
                ];
            }

            $encodedReport   = new SoapVar($report, SOAP_ENC_OBJECT, 'SearchQueryPerformanceReportRequest', $this->serviceProxy->GetNamespace());

            $reportRequest = $this->submitGenerateReport($encodedReport);

            if ($reportRequest) {
                $reportRequestId = $reportRequest->ReportRequestId;
            }
        } catch (SoapFault $e) {
            printf("-----\r\nFault Code: %s\r\nFault String: %s\r\nFault Detail: \r\n", $e->faultcode, $e->faultstring);

            if (isset($e->detail)) {
                var_dump($e->detail);
            }

            // print "-----\r\nLast SOAP request/response:\r\n";
            // print $this->serviceProxy->GetWsdl() . "\r\n";
            // print $this->serviceProxy->__getLastRequest()."\r\n";
            // print $this->serviceProxy->__getLastResponse()."\r\n";
        }

        return (new ReportDownload($this->serviceProxy, $reportRequestId));
    }


    /**
     * buildKeywordReport()
     *
     *
     */
    public function buildKeywordReport($aggregation = ReportAggregation::Daily)
    {
        $reportRequestId = null;

        try {
            $report                         = new KeywordPerformanceReportRequest();
            $report->ReportName             = 'Keyword Performance Report';
            $report->Format                 = ReportFormat::Csv;
            $report->ReturnOnlyCompleteData = false;
            $report->Aggregation            = $aggregation;

            $report->Scope                  = new AccountThroughAdGroupReportScope();
            $report->Scope->AccountIds      = [$this->service->getClientId()];

            $report->Time                               = new ReportTime();
            $report->Time->CustomDateRangeStart         = new Date();
            $report->Time->CustomDateRangeStart->Day    = date('d', strtotime($this->dateRange[0]));
            $report->Time->CustomDateRangeStart->Month  = date('m', strtotime($this->dateRange[0]));
            $report->Time->CustomDateRangeStart->Year   = date('Y', strtotime($this->dateRange[0]));

            $report->Time->CustomDateRangeEnd           = new Date();
            $report->Time->CustomDateRangeEnd->Day      = date('d', strtotime($this->dateRange[1]));
            $report->Time->CustomDateRangeEnd->Month    = date('m', strtotime($this->dateRange[1]));
            $report->Time->CustomDateRangeEnd->Year     = date('Y', strtotime($this->dateRange[1]));

            if (!empty($this->fields)) {
                $report->Columns = $this->fields;
            } else {
                $report->Columns = [
                    KeywordPerformanceReportColumn::TimePeriod,
                    KeywordPerformanceReportColumn::Clicks,
                    KeywordPerformanceReportColumn::Impressions,
                    KeywordPerformanceReportColumn::Spend,
                    KeywordPerformanceReportColumn::Conversions,
                    KeywordPerformanceReportColumn::Revenue,
                    KeywordPerformanceReportColumn::Keyword
                ];
            }

            $encodedReport = new SoapVar($report, SOAP_ENC_OBJECT, 'KeywordPerformanceReportRequest', $this->serviceProxy->GetNamespace());

            $reportRequest = $this->submitGenerateReport($encodedReport);

            if ($reportRequest) {
                $reportRequestId = $reportRequest->ReportRequestId;
            }
        } catch (SoapFault $e) {
            printf("-----\r\nFault Code: %s\r\nFault String: %s\r\nFault Detail: \r\n", $e->faultcode, $e->faultstring);

            if (isset($e->detail)) {
                var_dump($e->detail);
            }

            // print "-----\r\nLast SOAP request/response:\r\n";
            // print $this->serviceProxy->GetWsdl() . "\r\n";
            // print $this->serviceProxy->__getLastRequest()."\r\n";
            // print $this->serviceProxy->__getLastResponse()."\r\n";
        }

        return (new ReportDownload($this->serviceProxy, $reportRequestId));
    }


    /**
     * buildAgeRangeReport()
     *
     *
     */
    public function buildAgeGenderReport()
    {
        $reportRequestId = null;

        try {
            $report                         = new AgeGenderAudienceReportRequest();
            $report->ReportName             = 'Age Gender Performance Report';
            $report->Format                 = ReportFormat::Csv;
            $report->ReturnOnlyCompleteData = false;
            $report->Aggregation            = ReportAggregation::Summary;

            $report->Scope                  = new AccountThroughAdGroupReportScope();
            $report->Scope->AccountIds      = [$this->service->getClientId()];

            $report->Time                               = new ReportTime();
            $report->Time->CustomDateRangeStart         = new Date();
            $report->Time->CustomDateRangeStart->Day    = date('d', strtotime($this->dateRange[0]));
            $report->Time->CustomDateRangeStart->Month  = date('m', strtotime($this->dateRange[0]));
            $report->Time->CustomDateRangeStart->Year   = date('Y', strtotime($this->dateRange[0]));

            $report->Time->CustomDateRangeEnd           = new Date();
            $report->Time->CustomDateRangeEnd->Day      = date('d', strtotime($this->dateRange[1]));
            $report->Time->CustomDateRangeEnd->Month    = date('m', strtotime($this->dateRange[1]));
            $report->Time->CustomDateRangeEnd->Year     = date('Y', strtotime($this->dateRange[1]));

            if (!empty($this->fields)) {
                $report->Columns = $this->fields;
            } else {
                $report->Columns = [
                    AgeGenderAudienceReportColumn::AccountName,
                    AgeGenderAudienceReportColumn::AdGroupName,
                    AgeGenderAudienceReportColumn::AgeGroup,
                    AgeGenderAudienceReportColumn::Gender,
                    AgeGenderAudienceReportColumn::Clicks,
                    AgeGenderAudienceReportColumn::Impressions,
                    AgeGenderAudienceReportColumn::Spend,
                    AgeGenderAudienceReportColumn::Conversions,
                    AgeGenderAudienceReportColumn::Revenue
                ];
            }

            $encodedReport   = new SoapVar($report, SOAP_ENC_OBJECT, 'AgeGenderAudienceReportRequest', $this->serviceProxy->GetNamespace());

            $reportRequest = $this->submitGenerateReport($encodedReport);

            if ($reportRequest) {
                $reportRequestId = $reportRequest->ReportRequestId;
            }
        } catch (SoapFault $e) {
            printf("-----\r\nFault Code: %s\r\nFault String: %s\r\nFault Detail: \r\n", $e->faultcode, $e->faultstring);

            if (isset($e->detail)) {
                var_dump($e->detail);
            }

            // print "-----\r\nLast SOAP request/response:\r\n";
            // print $this->serviceProxy->GetWsdl() . "\r\n";
            // print $this->serviceProxy->__getLastRequest()."\r\n";
            // print $this->serviceProxy->__getLastResponse()."\r\n";
        }

        return (new ReportDownload($this->serviceProxy, $reportRequestId));
    }


    /**
     * buildGeoReport()
     *
     *
     */
    public function buildMostSpecificLocationReport()
    {
        $reportRequestId = null;

        try {
            $report                         = new GeographicPerformanceReportRequest();
            $report->ReportName             = 'Most Specific Location Performance Report';
            $report->Format                 = ReportFormat::Csv;
            $report->ReturnOnlyCompleteData = false;
            $report->Aggregation            = ReportAggregation::Summary;

            $report->Scope                  = new AccountThroughAdGroupReportScope();
            $report->Scope->AccountIds      = [$this->service->getClientId()];

            $report->Time                               = new ReportTime();
            $report->Time->CustomDateRangeStart         = new Date();
            $report->Time->CustomDateRangeStart->Day    = date('d', strtotime($this->dateRange[0]));
            $report->Time->CustomDateRangeStart->Month  = date('m', strtotime($this->dateRange[0]));
            $report->Time->CustomDateRangeStart->Year   = date('Y', strtotime($this->dateRange[0]));

            $report->Time->CustomDateRangeEnd           = new Date();
            $report->Time->CustomDateRangeEnd->Day      = date('d', strtotime($this->dateRange[1]));
            $report->Time->CustomDateRangeEnd->Month    = date('m', strtotime($this->dateRange[1]));
            $report->Time->CustomDateRangeEnd->Year     = date('Y', strtotime($this->dateRange[1]));

            if (!empty($this->fields)) {
                $report->Columns = $this->fields;
            } else {
                $report->Columns = [
                    GeographicPerformanceReportColumn::AccountName,
                    GeographicPerformanceReportColumn::LocationType,
                    GeographicPerformanceReportColumn::MostSpecificLocation,
                    GeographicPerformanceReportColumn::Country,
                    GeographicPerformanceReportColumn::State,
                    GeographicPerformanceReportColumn::MetroArea,
                    GeographicPerformanceReportColumn::City,
                    GeographicPerformanceReportColumn::County,
                    GeographicPerformanceReportColumn::PostalCode,
                    GeographicPerformanceReportColumn::LocationId,
                    GeographicPerformanceReportColumn::Clicks,
                    GeographicPerformanceReportColumn::Impressions,
                    GeographicPerformanceReportColumn::Spend,
                    GeographicPerformanceReportColumn::Conversions,
                    GeographicPerformanceReportColumn::Revenue
                ];
            }

            $encodedReport   = new SoapVar($report, SOAP_ENC_OBJECT, 'GeographicPerformanceReportRequest', $this->serviceProxy->GetNamespace());

            $reportRequest = $this->submitGenerateReport($encodedReport);

            if ($reportRequest) {
                $reportRequestId = $reportRequest->ReportRequestId;
            }
        } catch (SoapFault $e) {
            printf("-----\r\nFault Code: %s\r\nFault String: %s\r\nFault Detail: \r\n", $e->faultcode, $e->faultstring);

            if (isset($e->detail)) {
                var_dump($e->detail);
            }

            // print "-----\r\nLast SOAP request/response:\r\n";
            // print $this->serviceProxy->GetWsdl() . "\r\n";
            // print $this->serviceProxy->__getLastRequest()."\r\n";
            // print $this->serviceProxy->__getLastResponse()."\r\n";
        }

        return (new ReportDownload($this->serviceProxy, $reportRequestId));
    }




    /**
     * getAccountReport()
     *
     *
     */
    public function getAccountReport($aggregation = ReportAggregation::Daily)
    {
        return $this->buildAccountReport($aggregation)->toCollection();
    }


    /**
     * getCampaignReport()
     *
     *
     */
    public function getCampaignReport()
    {
        return $this->buildCampaignReport()->toCollection();
    }


    /**
     * getAdGroupReport()
     *
     *
     */
    public function getAdGroupReport()
    {
        return $this->buildAdGroupReport()->toCollection();
    }


    /**
     * getFinalUrlReport()
     *
     *
     */
    public function getFinalUrlReport()
    {
        return $this->buildFinalUrlReport()->toCollection();
    }


    /**
     * getSearchTermReport()
     *
     *
     */
    public function getSearchTermReport($aggregation = ReportAggregation::Summary)
    {
        return $this->buildSearchTermReport($aggregation)->toCollection();
    }


    /**
     * getKeywordReport()
     *
     *
     */
    public function getKeywordReport($aggregation = ReportAggregation::Daily)
    {
        return $this->buildKeywordReport($aggregation)->toCollection();
    }


    /**
     * getAgeRangeReport()
     *
     *
     */
    public function getAgeRangeReport()
    {
        return $this->buildAgeGenderReport()->aggregate('age_range');
    }


    /**
     * getGenderReport()
     *
     *
     */
    public function getGenderReport()
    {
        return $this->buildAgeGenderReport()->aggregate('gender');
    }


    /**
     * getMostSpecificLocationReport()
     *
     *
     */
    public function getMostSpecificLocationReport()
    {
        return $this->buildMostSpecificLocationReport()->toCollection();
    }
}
