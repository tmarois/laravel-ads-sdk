<?php namespace LaravelAds\Services\BingAds;

use SoapVar;
use SoapFault;
use Exception;
use ZipArchive;

use Microsoft\BingAds\Auth\ServiceClient;
use Microsoft\BingAds\Auth\ServiceClientType;

use Microsoft\BingAds\V12\Reporting\SubmitGenerateReportRequest;
use Microsoft\BingAds\V12\Reporting\PollGenerateReportRequest;
use Microsoft\BingAds\V12\Reporting\AccountPerformanceReportRequest;
use Microsoft\BingAds\V12\Reporting\AudiencePerformanceReportRequest;
use Microsoft\BingAds\V12\Reporting\KeywordPerformanceReportRequest;
use Microsoft\BingAds\V12\Reporting\CampaignPerformanceReportRequest;
use Microsoft\BingAds\V12\Reporting\AdGroupPerformanceReportRequest;
use Microsoft\BingAds\V12\Reporting\ReportFormat;
use Microsoft\BingAds\V12\Reporting\ReportAggregation;
use Microsoft\BingAds\V12\Reporting\AccountThroughAdGroupReportScope;
use Microsoft\BingAds\V12\Reporting\CampaignReportScope;
use Microsoft\BingAds\V12\Reporting\AdGroupReportScope;
use Microsoft\BingAds\V12\Reporting\AccountReportScope;
use Microsoft\BingAds\V12\Reporting\ReportTime;
use Microsoft\BingAds\V12\Reporting\ReportTimePeriod;
use Microsoft\BingAds\V12\Reporting\Date;
use Microsoft\BingAds\V12\Reporting\AccountPerformanceReportFilter;
use Microsoft\BingAds\V12\Reporting\KeywordPerformanceReportFilter;
use Microsoft\BingAds\V12\Reporting\CampaignPerformanceReportFilter;
use Microsoft\BingAds\V12\Reporting\AdGroupPerformanceReportFilter;
use Microsoft\BingAds\V12\Reporting\DeviceTypeReportFilter;
use Microsoft\BingAds\V12\Reporting\AccountPerformanceReportColumn;
use Microsoft\BingAds\V12\Reporting\AudiencePerformanceReportColumn;
use Microsoft\BingAds\V12\Reporting\CampaignPerformanceReportColumn;
use Microsoft\BingAds\V12\Reporting\AdGroupPerformanceReportColumn;
use Microsoft\BingAds\V12\Reporting\KeywordPerformanceReportColumn;
use Microsoft\BingAds\V12\Reporting\ReportRequestStatusType;
use Microsoft\BingAds\V12\Reporting\KeywordPerformanceReportSort;
use Microsoft\BingAds\V12\Reporting\SortOrder;

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

        $this->serviceProxy = $this->service->serviceProxy(ServiceClientType::ReportingVersion12);
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
        if ($auto == false)
        {
            $this->fields = $fields;
        }

        if ($auto == true && empty($this->fields))
        {
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
    public function buildAccountReport()
    {
        $report                         = new AccountPerformanceReportRequest();
        $report->ReportName             = 'Account Performance Report';
        $report->Format                 = ReportFormat::Csv;
        $report->ReturnOnlyCompleteData = false;
        $report->Aggregation            = ReportAggregation::Daily;

        $report->Scope                  = new AccountReportScope();
        $report->Scope->AccountIds      = [$this->service->getClientId()];

        $report->Time                               = new ReportTime();
        $report->Time->CustomDateRangeStart         = new Date();
        $report->Time->CustomDateRangeStart->Day    = date('d',strtotime($this->dateRange[0]));
        $report->Time->CustomDateRangeStart->Month  = date('m',strtotime($this->dateRange[0]));
        $report->Time->CustomDateRangeStart->Year   = date('Y',strtotime($this->dateRange[0]));

        $report->Time->CustomDateRangeEnd           = new Date();
        $report->Time->CustomDateRangeEnd->Day      = date('d',strtotime($this->dateRange[1]));
        $report->Time->CustomDateRangeEnd->Month    = date('m',strtotime($this->dateRange[1]));
        $report->Time->CustomDateRangeEnd->Year     = date('Y',strtotime($this->dateRange[1]));

        $report->Columns = array (
            AccountPerformanceReportColumn::TimePeriod,
            AccountPerformanceReportColumn::AccountId,
            AccountPerformanceReportColumn::Clicks,
            AccountPerformanceReportColumn::Impressions,
            AccountPerformanceReportColumn::Spend,
            AccountPerformanceReportColumn::Conversions,
            AccountPerformanceReportColumn::Revenue
        );

        $encodedReport   = new SoapVar($report, SOAP_ENC_OBJECT, 'AccountPerformanceReportRequest', $this->serviceProxy->GetNamespace());
        $reportRequestId = $this->submitGenerateReport($encodedReport)->ReportRequestId;

        $waitTime = 15 * 1;
        $reportRequestStatus = null;
        $reportName   = time();
        $DownloadPath = storage_path("app/".$reportName.'.zip');

        return (new ReportDownload($this->serviceProxy, $reportRequestId));
    }


    /**
     * buildCampaignReport()
     *
     *
     */
    public function buildCampaignReport()
    {
        $report                         = new CampaignPerformanceReportRequest();
        $report->ReportName             = 'Campaign Performance Report';
        $report->Format                 = ReportFormat::Csv;
        $report->ReturnOnlyCompleteData = false;
        $report->Aggregation            = ReportAggregation::Daily;

        $report->Scope                  = new CampaignReportScope();
        $report->Scope->AccountIds      = [$this->service->getClientId()];

        $report->Time                               = new ReportTime();
        $report->Time->CustomDateRangeStart         = new Date();
        $report->Time->CustomDateRangeStart->Day    = date('d',strtotime($this->dateRange[0]));
        $report->Time->CustomDateRangeStart->Month  = date('m',strtotime($this->dateRange[0]));
        $report->Time->CustomDateRangeStart->Year   = date('Y',strtotime($this->dateRange[0]));

        $report->Time->CustomDateRangeEnd           = new Date();
        $report->Time->CustomDateRangeEnd->Day      = date('d',strtotime($this->dateRange[1]));
        $report->Time->CustomDateRangeEnd->Month    = date('m',strtotime($this->dateRange[1]));
        $report->Time->CustomDateRangeEnd->Year     = date('Y',strtotime($this->dateRange[1]));

        $report->Columns = array (
            CampaignPerformanceReportColumn::TimePeriod,
            CampaignPerformanceReportColumn::AccountId,
            CampaignPerformanceReportColumn::CampaignName,
            CampaignPerformanceReportColumn::CampaignId,
            CampaignPerformanceReportColumn::CampaignStatus,
            CampaignPerformanceReportColumn::Clicks,
            CampaignPerformanceReportColumn::Impressions,
            CampaignPerformanceReportColumn::Spend,
            CampaignPerformanceReportColumn::Conversions,
            CampaignPerformanceReportColumn::Revenue
        );

        $encodedReport   = new SoapVar($report, SOAP_ENC_OBJECT, 'CampaignPerformanceReportRequest', $this->serviceProxy->GetNamespace());
        $reportRequestId = $this->submitGenerateReport($encodedReport)->ReportRequestId;

        $waitTime = 15 * 1;
        $reportRequestStatus = null;
        $reportName   = time();
        $DownloadPath = storage_path("app/".$reportName.'.zip');

        return (new ReportDownload($this->serviceProxy, $reportRequestId));
    }


    /**
     * buildCampaignReport()
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
        $report->Time->CustomDateRangeStart->Day    = date('d',strtotime($this->dateRange[0]));
        $report->Time->CustomDateRangeStart->Month  = date('m',strtotime($this->dateRange[0]));
        $report->Time->CustomDateRangeStart->Year   = date('Y',strtotime($this->dateRange[0]));

        $report->Time->CustomDateRangeEnd           = new Date();
        $report->Time->CustomDateRangeEnd->Day      = date('d',strtotime($this->dateRange[1]));
        $report->Time->CustomDateRangeEnd->Month    = date('m',strtotime($this->dateRange[1]));
        $report->Time->CustomDateRangeEnd->Year     = date('Y',strtotime($this->dateRange[1]));

        $report->Columns = array (
            AdGroupPerformanceReportColumn::TimePeriod,
            AdGroupPerformanceReportColumn::AccountId,
            AdGroupPerformanceReportColumn::CampaignId,
            AdGroupPerformanceReportColumn::AdGroupId,
            AdGroupPerformanceReportColumn::AdGroupName,
            AdGroupPerformanceReportColumn::Clicks,
            AdGroupPerformanceReportColumn::Impressions,
            AdGroupPerformanceReportColumn::Spend,
            AdGroupPerformanceReportColumn::Conversions,
            AdGroupPerformanceReportColumn::Revenue
        );

        $encodedReport   = new SoapVar($report, SOAP_ENC_OBJECT, 'AdGroupPerformanceReportRequest', $this->serviceProxy->GetNamespace());
        $reportRequestId = $this->submitGenerateReport($encodedReport)->ReportRequestId;

        $waitTime = 15 * 1;
        $reportRequestStatus = null;
        $reportName   = time();
        $DownloadPath = storage_path("app/".$reportName.'.zip');

        return (new ReportDownload($this->serviceProxy, $reportRequestId));
    }




    /**
     * getAccountReport()
     *
     *
     */
    public function getAccountReport()
    {
        return $this->buildAccountReport()->toCollection();
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

}
