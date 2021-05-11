<?php

namespace LaravelAds\Services\GoogleAds;

use Google\AdsApi\AdWords\Reporting\v201809\ReportDownloader;
use Google\AdsApi\AdWords\Reporting\v201809\ReportDefinition;
use Google\AdsApi\AdWords\ReportSettingsBuilder;

class ReportDownload
{
    /**
     * $service
     *
     */
    protected $service = null;

    /**
     * $results
     *
     */
    protected $results = null;

    /**
     * __construct()
     *
     *
     */
    public function __construct($service, ReportDefinition $reportDefinition)
    {
        $this->service = $service;

        $reportDownloader = new ReportDownloader($this->service->session());

        $reportSettingsOverride = (new ReportSettingsBuilder())
            ->includeZeroImpressions(false)
            ->skipReportHeader(true)
            ->skipReportSummary(true)
            ->build();

        $reportDownloadResult = $reportDownloader->downloadReport($reportDefinition, $reportSettingsOverride);

        $this->results = $reportDownloadResult->getAsString();
    }

    /**
     * getResults()
     *
     *
     * @return string results
     */
    public function getResults()
    {
        return $this->results ?? false;
    }

    /**
     * aggregate()
     *
     *
     * @return collection results
     */
    public function aggregate($field)
    {
        $results = $this->toArray();

        $only = [
            'impressions','clicks','cost','conversions','conversion_value'
        ];

        $r = [];
        foreach ($results as $key=>$value) {
            if (isset($r[$value[$field]])) {
                $x = $r[$value[$field]];

                foreach ($value as $k=>$v) {
                    if (!in_array($k, $only)) {
                        continue;
                    }

                    $n = $x[$k];
                    if (!is_numeric($n)) {
                        continue 2;
                    }
                    if (!is_numeric($v)) {
                        continue 2;
                    }

                    $value[$k] = $v+$n;
                }

                $r[$value[$field]] = $value;
            } else {
                $r[$value[$field]] = $value;
            }
        }

        return collect($r);
    }

    /**
     * toArray()
     *
     *
     * @return array results
     */
    public function toArray()
    {
        if (is_array($this->results)) {
            return $this->results;
        }

        // get all rows
        $rows = array_map('str_getcsv', explode("\n", $this->results));
        // get the header row
        $headers = $rows[0];
        // remove first row (headers)
        array_shift($rows);

        $header = [];
        foreach ($headers as $label) {
            $label = strtolower($label);

            switch ($label) {
                case 'day': $label = 'date'; break;
                case 'campaign id': $label = 'campaign_id'; break;
                case 'campaign state': $label = 'campaign_status'; break;
                case 'campaign': $label = 'campaign_name'; break;
                case 'ad group': $label = 'ad_group_name'; break;
                case 'advertising channel': $label = 'channel'; break;
                case 'ad group id': $label = 'ad_group_id'; break;
                case 'total conv. value': $label = 'conversion_value'; break;
                case 'avg. position': $label = 'avg_position'; break;

                case 'hour of day': $label = 'hour'; break;
                case 'day of week': $label = 'day'; break;

                // criteria
                case 'age range': $label = 'age_range'; break;
                case 'searchterm': $label = 'search_term'; break;
                case 'most specific location': $label = 'location'; break;
                default:
            }

            $header[] = str_replace([' ','/'], '_', $label);
        }

        $report = [];
        foreach ($rows as $index=>$columns) {
            $r = [];
            foreach ($columns as $index2=>$cs) {
                if (!isset($header[$index2])) {
                    continue;
                }

                $n = $header[$index2];

                if ($n == 'cost') {
                    $cs = round(intval($cs) / 1000000, 2);
                }

                $r[$n] = $cs;
            }

            // add in columns (headers) that are missing from the response
            foreach ($header as $h) {
                if (!isset($r[$h])) {
                    $r[$h] = 0;
                }
            }

            $report[] = $r;
        }

        ksort($report);

        return $report;
    }

    /**
     * toCollection()
     *
     *
     * @return \Illuminate\Support\Collection results
     */
    public function toCollection()
    {
        return collect($this->toArray());
    }
}
