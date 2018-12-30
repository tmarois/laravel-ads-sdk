<?php namespace LaravelAds\Services\GoogleAds;

use Google\AdsApi\AdWords\Reporting\v201802\ReportDownloader;
use Google\AdsApi\AdWords\Reporting\v201802\ReportDefinition;
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
     * toString()
     *
     *
     * @return string results
     */
    public function toString()
    {
        return $this->results ?? '';
    }

    /**
     * toArray()
     *
     *
     * @return array results
     */
    public function toArray()
    {
        $csv    = explode("\n",$this->results);
        $header = explode(',',$csv[0]);
        $csv    = array_filter($csv);

        $header = [];
        foreach(explode(',',$csv[0]) as $label)
        {
            $label = strtolower($label);

            switch($label) {
                case 'day' : $label = 'date'; break;
                case 'campaign id' : $label = 'campaign_id'; break;
                case 'campaign state' : $label = 'campaign_status'; break;
                case 'campaign' : $label = 'campaign_name'; break;
                case 'ad group' : $label = 'ad_group_name'; break;
                case 'advertising channel' : $label = 'channel'; break;
                case 'ad group id' : $label = 'ad_group_id'; break;
                case 'total conv. value' : $label = 'conversion_value'; break;
                default :
            }

            $header[] = str_replace(' ','_',$label);
        }

        unset($csv[0]);

        $report = [];
        foreach($csv as $index=>$row)
        {
            $columns = explode(',',$row);

            $r = [];
            foreach($columns as $index2=>$cs)
            {
                if (!isset($header[$index2])) continue;

                $n = $header[$index2];

                if ($n == 'cost') $cs = round( intval($cs) / 1000000,2);

                $r[$n] = $cs;
            }

            $report[] = $r;
        }

        ksort($report);

        return $report;
    }

}
