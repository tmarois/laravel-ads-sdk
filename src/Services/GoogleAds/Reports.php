<?php namespace LaravelAds\Services\GoogleAds;


use LaravelAds\Services\GoogleAds\ReportDownload;

use Google\AdsApi\AdWords\Reporting\v201802\DownloadFormat;
use Google\AdsApi\AdWords\Reporting\v201802\ReportDefinition;
use Google\AdsApi\AdWords\Reporting\v201802\ReportDefinitionDateRangeType;
use Google\AdsApi\AdWords\Reporting\v201802\ReportDownloader;
use Google\AdsApi\AdWords\v201802\cm\Predicate;
use Google\AdsApi\AdWords\v201802\cm\PredicateOperator;
use Google\AdsApi\AdWords\v201802\cm\ReportDefinitionReportType;
use Google\AdsApi\AdWords\v201802\cm\Selector;

class Reports
{
    /**
     * $service
     *
     */
    protected $service = null;

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

    /**
     * selectors()
     *
     *
     */
    protected function getSelector($dateRange, $fields = [])
    {
        $selector = new Selector();
        $selector->setFields($fields);
        $selector->setDateRange([
            'min' => str_replace('-','',$dateRange[0]),
            'max' => str_replace('-','',$dateRange[1])
        ]);

        return $selector;
    }

    /**
     * reportDefinition()
     *
     *
     */
    protected function reportDefinition($reportType)
    {
        $reportDefinition = new ReportDefinition();
        $reportDefinition->setSelector($this->getSelector($this->dateRange, $this->fields));
        $reportDefinition->setReportName('Performance report #' . uniqid());
        $reportDefinition->setDateRangeType(ReportDefinitionDateRangeType::CUSTOM_DATE);
        $reportDefinition->setReportType($reportType);
        $reportDefinition->setDownloadFormat(DownloadFormat::CSV);

        return $reportDefinition;
    }


    /**
     * reportDownload()
     *
     *
     */
    protected function reportDownload($reportDefinition)
    {
        return (new ReportDownload($this->service, $reportDefinition));
    }


    /**
     * getAccountReport()
     * https://developers.google.com/adwords/api/docs/appendix/reports/account-performance-report
     *
     *
     */
    public function getAccountReport()
    {
        $this->setFields([
            'Date',
            'Impressions',
            'Clicks',
            'Cost',
            'Conversions',
            'ConversionValue',
            'AllConversions',
            'AllConversionValue'
        ],true);

        $reportDefinition = $this->reportDefinition(ReportDefinitionReportType::ACCOUNT_PERFORMANCE_REPORT);

        return $this->reportDownload($reportDefinition);
    }


    /**
     * getCampaignReport()
     * https://developers.google.com/adwords/api/docs/appendix/reports/campaign-performance-report
     *
     *
     */
    public function getCampaignReport()
    {
        $this->setFields([
            'Date',
            'AdvertisingChannelType',
            'CampaignStatus',
            'CampaignName',
            'CampaignId',
            'Impressions',
            'Clicks',
            'Cost',
            'Conversions',
            'ConversionValue',
            'AllConversions',
            'AllConversionValue'
        ],true);

        $reportDefinition = $this->reportDefinition(ReportDefinitionReportType::CAMPAIGN_PERFORMANCE_REPORT);

        return $this->reportDownload($reportDefinition);
    }


    /**
     * getAdGroupReport()
     * https://developers.google.com/adwords/api/docs/appendix/reports/adgroup-performance-report
     *
     *
     */
    public function getAdGroupReport()
    {
        $this->setFields([
            'Date',
            'AdGroupId',
            'AdGroupName',
            'CampaignId',
            'Impressions',
            'Clicks',
            'Cost',
            'Conversions',
            'ConversionValue',
            'AllConversions',
            'AllConversionValue'
        ],true);

        $reportDefinition = $this->reportDefinition(ReportDefinitionReportType::ADGROUP_PERFORMANCE_REPORT);

        return $this->reportDownload($reportDefinition);
    }

}
