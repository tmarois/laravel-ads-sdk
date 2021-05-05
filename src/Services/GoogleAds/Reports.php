<?php

namespace LaravelAds\Services\GoogleAds;

use LaravelAds\Services\GoogleAds\ReportDownload;

use Google\AdsApi\AdWords\Reporting\v201809\DownloadFormat;
use Google\AdsApi\AdWords\Reporting\v201809\ReportDefinition;
use Google\AdsApi\AdWords\Reporting\v201809\ReportDefinitionDateRangeType;
use Google\AdsApi\AdWords\Reporting\v201809\ReportDownloader;
use Google\AdsApi\AdWords\v201809\cm\Predicate;
use Google\AdsApi\AdWords\v201809\cm\PredicateOperator;
use Google\AdsApi\AdWords\v201809\cm\ReportDefinitionReportType;
use Google\AdsApi\AdWords\v201809\cm\Selector;

//https://developers.google.com/adwords/api/docs/appendix/reports

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
     * $filters
     *
     */
    protected $filters = [];

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
        if ($auto == false) {
            $this->fields = $fields;
        }

        if ($auto == true && empty($this->fields)) {
            $this->fields = $fields;
        }

        return $this;
    }

    /**
     * setFilters()
     *
     *
     * @return self
     */
    public function setFilters($filters)
    {
        $this->filters = $filters;

        return $this;
    }

    /**
     * selectors()
     *
     *
     */
    protected function getSelector($dateRange, $fields = [], $filters = [])
    {
        $selector = new Selector();
        $selector->setFields($fields);
        $selector->setDateRange([
            'min' => str_replace('-', '', $dateRange[0]),
            'max' => str_replace('-', '', $dateRange[1])
        ]);

        if ($filters) {
            $predicates = [];

            foreach ($filters as $filter) {
                $predicates[] = new Predicate($filter['field'], PredicateOperator::IN, $filter['values']);
            }

            $selector->setPredicates($predicates);
        }

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
        $reportDefinition->setSelector($this->getSelector($this->dateRange, $this->fields, $this->filters));
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
    public function reportDownload($reportType)
    {
        $reportDefinition = $this->reportDefinition($reportType);

        return (new ReportDownload($this->service, $reportDefinition));
    }


    /**
     * getAccountReport()
     * https://developers.google.com/adwords/api/docs/appendix/reports/account-performance-report
     *
     *
     */
    public function getAccountReport($aggregation = 'Date')
    {
        $this->setFields([
            $aggregation,
            'Impressions',
            'Clicks',
            'Cost',
            'Conversions',
            'ConversionValue'
        ], true);

        return $this->reportDownload(ReportDefinitionReportType::ACCOUNT_PERFORMANCE_REPORT)->toCollection();
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
            'ConversionValue'
        ], true);

        return $this->reportDownload(ReportDefinitionReportType::CAMPAIGN_PERFORMANCE_REPORT)->toCollection();
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
            'CampaignName',
            'Impressions',
            'Clicks',
            'Cost',
            'Conversions',
            'ConversionValue',
            'AveragePosition'
        ], true);

        return $this->reportDownload(ReportDefinitionReportType::ADGROUP_PERFORMANCE_REPORT)->toCollection();
    }

    /**
     * getFinalUrlReport()
     *
     */
    public function getFinalUrlReport()
    {
        $this->setFields([
            'Date',
            'CampaignId',
            'CampaignName',
            'Impressions',
            'Clicks',
            'Cost',
            'Conversions',
            'ConversionValue',
            'EffectiveFinalUrl'
        ], true);

        return $this->reportDownload(ReportDefinitionReportType::FINAL_URL_REPORT)->toCollection();
    }


    /**
     * getAgeRangeReport()
     * https://developers.google.com/adwords/api/docs/appendix/reports/age-range-performance-report
     *
     *
     */
    public function getAgeRangeReport()
    {
        return $this->getCriteriaReport(ReportDefinitionReportType::AGE_RANGE_PERFORMANCE_REPORT, 'age_range', 'Criteria');
    }

    /**
     * getGenderReport()
     *
     */
    public function getGenderReport()
    {
        return $this->getCriteriaReport(ReportDefinitionReportType::GENDER_PERFORMANCE_REPORT, 'gender', 'Criteria');
    }

    /**
     * getPlacementReport()
     *
     */
    public function getPlacementReport()
    {
        return $this->getCriteriaReport(ReportDefinitionReportType::PLACEMENT_PERFORMANCE_REPORT, 'placement', 'Criteria');
    }

    /**
     * getPlacementUrlReport()
     *
     */
    public function getPlacementUrlReport()
    {
        return $this->getCriteriaReport(ReportDefinitionReportType::URL_PERFORMANCE_REPORT, 'url', 'Url');
    }

    /**
     * getSearchTermReport()
     *
     */
    public function getSearchTermReport()
    {
        return $this->getCriteriaReport(ReportDefinitionReportType::SEARCH_QUERY_PERFORMANCE_REPORT, 'search_term', 'Query');
    }


    /**
     * getCriteriaReport()
     *
     */
    public function getCriteriaReport($report, $field, $setField)
    {
        $this->setFields([
            $setField,
            'Impressions',
            'Clicks',
            'Cost',
            'Conversions',
            'ConversionValue'
        ], true);

        return $this->reportDownload($report)->aggregate($field);
    }
}
