<?php

namespace LaravelAds\Services\BingAds\Operations;

use SoapVar;
use SoapFault;
use Exception;

use LaravelAds\Services\BingAds\Service;

use Microsoft\BingAds\Auth\ServiceClient;
use Microsoft\BingAds\Auth\ServiceClientType;

use Microsoft\BingAds\V13\CampaignManagement\OfflineConversion;
use Microsoft\BingAds\V13\CampaignManagement\ApplyOfflineConversionsRequest;

class OfflineConversions
{
    /**
     * $service
     *
     */
    protected $service = null;

    /**
     * $offlineConversions
     *
     */
    protected $offlineConversions = [];

    /**
     * $mutations
     *
     */
    protected $mutations = [];

    /**
     * __construct()
     *
     */
    public function __construct(Service $service = null)
    {
        $this->service = $service;
    }

    /**
     * getConversions()
     *
     * @return array
     */
    public function getConversions()
    {
        return $this->offlineConversions;
    }

    /**
     * addBulk()
     *
     * @return OfflineConversions
     */
    public function addBulk(array $conversions = [])
    {
        foreach ($conversions as $conversion) {
            $this->add($conversion);
        }

        return $this;
    }

    /**
     * add()
     *
     * @return OfflineConversions
     */
    public function add(array $conversions = [])
    {
        $gc = new OfflineConversion();
        $gc->ConversionName   = $conversions['name'];
        $gc->ConversionTime   = $conversions['time'];
        $gc->ConversionValue  = $conversions['value'];
        $gc->MicrosoftClickId = $conversions['click_id'];

        $this->offlineConversions[] = $conversions;
        $this->mutations[] = $gc;

        return $this;
    }

    /**
     * upload()
     *
     */
    public function upload($outputValue = false)
    {
        $errorResponse = [];
        $successResponse = [];

        foreach ($this->mutations as $i=>$mutate) {
            $click = $this->offlineConversions[$i] ?? [];

            try {
                $serviceCall = $this->service->call(ServiceClientType::CampaignManagementVersion13);

                $request = new ApplyOfflineConversionsRequest();
                $request->OfflineConversions = [$mutate];

                $result = $serviceCall->GetService()->ApplyOfflineConversions($request);

                if (isset($result->PartialErrors->BatchError)) {
                    $errorResponse[$i] = [
                        'name' => $click['name'] ?? '',
                        'time' => $click['time'] ?? '',
                        'click_id' => $click['click_id'],
                        'value' => $click['value'] ?? 0,
                        'error' => $result->PartialErrors->BatchError[0]->ErrorCode ?? 'unknown'
                    ];
                } else {
                    if ($outputValue==true) {
                        $successResponse[$i] = [
                            'name' => $click['name'],
                            'time' => $click['time'],
                            'click_id' => $click['click_id'],
                            'value' => $click['value'] ?? 0,
                        ];
                    } else {
                        $successResponse[$i] = $click['click_id'];
                    }
                }
            } catch (SoapFault $e) {
                // printf("-----\r\nFault Code: %s\r\nFault String: %s\r\nFault Detail: \r\n", $e->faultcode, $e->faultstring);
                // var_dump($e->detail);

                // display generic error code
                $errorCode = $e->faultcode;

                // display auth error
                if (isset($e->detail->AdApiFaultDetail->Errors->AdApiError->ErrorCode)) {
                    $errorCode = $e->detail->AdApiFaultDetail->Errors->AdApiError->ErrorCode;
                }

                if (isset($e->detail->ApiFaultDetail->OperationErrors->OperationError->ErrorCode)) {
                    $errorCode = $e->detail->ApiFaultDetail->OperationErrors->OperationError->ErrorCode;
                }

                $errorResponse[$i] = [
                    'name' => $click['name'] ?? '',
                    'time' => $click['time'] ?? '',
                    'click_id' => $click['click_id'],
                    'value' => $click['value'] ?? 0,
                    'error' => $errorCode
                ];

                // print "-----\r\nLast SOAP request/response:\r\n";
                // print $serviceCall->GetWsdl() . "\r\n";
                // print $serviceCall->GetService()->__getLastRequest()."\r\n";
                // print $serviceCall->GetService()->__getLastResponse()."\r\n";
            } catch (Exception $e) {
                $errorResponse[$i] = [
                    'name' => $click['name'] ?? '',
                    'time' => $click['time'] ?? '',
                    'click_id' => $click['click_id'],
                    'value' => $click['value'] ?? 0,
                    'error' => $e->getMessage()
                ];
            }
        }

        // prevent abuse in api requests
        // default is 0.05 seconds per request
        usleep(050000);

        return [
            'errors' => ($errorResponse) ? $errorResponse : false,
            'success' => ($successResponse) ? $successResponse : false
        ];
    }
}
