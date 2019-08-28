<?php namespace LaravelAds\Services\BingAds\Operations;

use SoapVar;
use SoapFault;
use Exception;

use LaravelAds\Services\BingAds\Service;

use Microsoft\BingAds\Auth\ServiceClient;
use Microsoft\BingAds\Auth\ServiceClientType;

use Microsoft\BingAds\V12\CampaignManagement\OfflineConversion;
use Microsoft\BingAds\V12\CampaignManagement\ApplyOfflineConversionsRequest;

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
        foreach($conversions as $conversion) {
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
    public function upload()
    {
        $errorResponse = [];
        $successResponse = [];

        foreach($this->mutations as $i=>$mutate)
        {
            $click = $this->offlineConversions[$i] ?? [];

            try 
            {
                $serviceCall = $this->service->call(ServiceClientType::CampaignManagementVersion12);

                $request = new ApplyOfflineConversionsRequest();
                $request->OfflineConversions = [$mutate];

                $result = $serviceCall->GetService()->ApplyOfflineConversions($request);

                if (isset($result->PartialErrors->BatchError))
                {
                    $errorResponse[$i] = [
                        'click_id' => $click['click_id'],
                        'error' => $result->PartialErrors->BatchError[0]->ErrorCode ?? 'unknown'
                    ];
                }
                else 
                {
                    $successResponse[$i] = $click['click_id'];
                }
            }
            catch (SoapFault $e)
            {
                // printf("-----\r\nFault Code: %s\r\nFault String: %s\r\nFault Detail: \r\n", $e->faultcode, $e->faultstring);
                // print_r($e->detail);

                // print_r($e->detail->ApiFaultDetail->TrackingId);

                $errorResponse[$i] = [
                    'click_id' => $click['click_id'],
                    'error' => $e->faultcode
                ];

                // print "-----\r\nLast SOAP request/response:\r\n";
                // print $serviceCall->GetWsdl() . "\r\n";
                // print $serviceCall->GetService()->__getLastRequest()."\r\n";
                // print $serviceCall->GetService()->__getLastResponse()."\r\n";
            }
            catch (Exception $e) 
            {
                $errorResponse[$i] = [
                    'click_id' => $click['click_id'],
                    'error' => $e->getMessage()
                ];
            }
        }

        // prevent abuse in api requests
        // default is 0.1 seconds per request
        usleep(100000);

        return [
            'errors' => ($errorResponse) ? $errorResponse : false,
            'success' => ($successResponse) ? $successResponse : false
        ];
    }
}
