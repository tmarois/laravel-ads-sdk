<?php

namespace LaravelAds\Services\BingAds;

use Exception;
use ZipArchive;
use Microsoft\BingAds\V13\Reporting\ReportRequestStatusType;
use Microsoft\BingAds\V13\Reporting\PollGenerateReportRequest;

class ReportDownload
{
    /**
     * $service
     *
     */
    protected $serviceProxy = null;

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
    public function __construct($serviceProxy, $reportId)
    {
        $this->serviceProxy = $serviceProxy;

        $waitTime = 10 * 1;
        $reportRequestStatus = null;
        $reportName   = time();
        $DownloadPath = storage_path("app/".$reportName.'.zip');

        // This sample polls every 30 seconds up to 5 minutes.
        // In production you may poll the status every 1 to 2 minutes for up to one hour.
        // If the call succeeds, stop polling. If the call or
        // download fails, the call throws a fault.

        for ($i = 0; $i < 120; $i++) {
            sleep($waitTime);

            $reportRequestStatus = $this->pollGenerateReport($this->serviceProxy, $reportId)->ReportRequestStatus;

            if ($reportRequestStatus->Status == ReportRequestStatusType::Success ||
                $reportRequestStatus->Status == ReportRequestStatusType::Error) {
                break;
            }
        }

        if ($reportRequestStatus != null) {
            if ($reportRequestStatus->Status == ReportRequestStatusType::Success) {
                $reportDownloadUrl = $reportRequestStatus->ReportDownloadUrl;

                if ($reportDownloadUrl == null) {
                    print "No report data for the submitted request\n";

                    $this->results = null;

                    return;
                } else {
                    $this->downloadFile($reportDownloadUrl, $DownloadPath);
                }
            } elseif ($reportRequestStatus->Status == ReportRequestStatusType::Error) {
                printf("The request failed. Try requesting the report " .
                        "later.\nIf the request continues to fail, contact support.\n");

                $this->results = null;

                return;
            } else {
                printf(
                    "The request is taking longer than expected.\n " .
                        "Save the report ID (%s) and try again later.\n",
                    $reportId
                );

                $this->results = null;

                return;
            }
        }

        $this->results = $this->extractZip($DownloadPath, $reportId);
    }


    protected function downloadFile($reportDownloadUrl, $downloadPath)
    {
        if (!$reader = fopen($reportDownloadUrl, 'rb')) {
            throw new Exception("Failed to open URL " . $reportDownloadUrl . ".");
        }

        if (!$writer = fopen($downloadPath, 'wb')) {
            fclose($reader);
            throw new Exception("Failed to create ZIP file " . $downloadPath . ".");
        }

        $bufferSize = 100 * 1024;
        while (!feof($reader)) {
            if (false === ($buffer = fread($reader, $bufferSize))) {
                fclose($reader);
                fclose($writer);
                throw new Exception("Read operation from URL failed.");
            }

            if (fwrite($writer, $buffer) === false) {
                fclose($reader);
                fclose($writer);
                $exception = new Exception("Write operation to ZIP file failed.");
            }
        }

        fclose($reader);
        fflush($writer);
        fclose($writer);
    }

    protected function extractZip($location, $name)
    {
        $zip = new ZipArchive;
        if ($zip->open($location) === true) {
            $zip->extractTo(storage_path('app/'));
            $zip->close();

            unlink($location);
        }

		// Sometimes the report id has a extension starting with _
		// if this occurs we will just remove it.
		$fileNameLength = strpos($name, '_') > 0 ? strpos($name, '_') : strlen($name);
		$filePath = storage_path('app/') . substr($name, 0, $fileNameLength) . '.csv';

		$data = file($filePath);
        unlink($filePath);

        return $data;
    }


    protected function pollGenerateReport($session, $reportRequestId)
    {
        $request = new PollGenerateReportRequest();
        $request->ReportRequestId = $reportRequestId;

        return $session->GetService()->PollGenerateReport($request);
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
     * aggregate()
     *
     *
     * @return collection results
     */
    public function aggregate($field)
    {
        $results = $this->toArray();

        $only = [
            'impressions',
            'clicks',
            'cost',
            'conversions',
            'conversion_value',
        ];

        $r = [];
        foreach ($results as $key => $value) {
            unset($value['date']);

            if (isset($r[$value[$field]])) {
                $x = $r[$value[$field]];

                foreach ($value as $k => $v) {
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
        if (!$this->results) {
            return [];
        }

        $csv = array_map('str_getcsv', $this->results);

        $h = $csv[10] ?? [];

        $header = [];
        foreach ($h as $label) {
            $label = strtolower($label);

            switch ($label) {
                case 'timeperiod': $label = 'date'; break;
                case 'accountid': $label = 'account_id'; break;
                case 'accountname': $label = 'account_name'; break;
                case 'campaignid': $label = 'campaign_id'; break;
                case 'campaignname': $label = 'campaign_name'; break;
                case 'campaignstatus': $label = 'campaign_status'; break;
                case 'adgroupid': $label = 'ad_group_id'; break;
                case 'adgroupname': $label = 'ad_group_name'; break;
                case 'spend': $label = 'cost'; break;
                case 'revenue': $label = 'conversion_value'; break;
                case 'averageposition': $label = 'avg_position'; break;
                case 'destinationurl': $label = 'destination_url'; break;
                case 'finalurl': $label = 'final_url'; break;
                case 'gender': $label = 'gender'; break;
                case 'agegroup': $label = 'age_range'; break;
                case 'searchquery': $label = 'search_term'; break;
                case 'locationtype': $label = 'location_type'; break;
                case 'mostspecificlocation': $label = 'location'; break;
                case 'metroarea': $label = 'metro_area'; break;
                case 'postalcode': $label = 'postal_code'; break;
                case 'locationid': $label = 'location_id'; break;
                default:
            }

            $header[] = str_replace(' ', '_', $label);
        }

        // wtf is this bing??
        unset($csv[0]);
        unset($csv[1]);
        unset($csv[2]);
        unset($csv[3]);
        unset($csv[4]);
        unset($csv[5]);
        unset($csv[6]);
        unset($csv[7]);
        unset($csv[8]);
        unset($csv[9]);
        unset($csv[10]);

        // more wtf rows...
        array_pop($csv);
        array_pop($csv);

        $report = [];
        foreach ($csv as $index => $columns) {
            $r = [];
            foreach ($columns as $index2=>$cs) {
                if (!isset($header[$index2])) {
                    continue;
                }

                $n = $header[$index2];

                $r[$n] = str_replace(',', '', $cs);
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
