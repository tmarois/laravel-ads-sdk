<?php namespace LaravelAds\Services\GoogleAds\Operations;

use LaravelAds\Services\GoogleAds\Service;

use Google\AdsApi\AdWords\v201809\cm\ApiException;
use Google\AdsApi\AdWords\v201809\cm\Operator;
use Google\AdsApi\AdWords\v201809\cm\OfflineConversionFeedService;
use Google\AdsApi\AdWords\v201809\cm\OfflineConversionFeed;
use Google\AdsApi\AdWords\v201809\cm\OfflineConversionFeedOperation;
use Google\AdsApi\AdWords\v201809\cm\OfflineConversionError;

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
        $gc = new OfflineConversionFeed();
        $gc->setConversionName($conversions['name']);
        $gc->setConversionTime($conversions['time']);
        $gc->setGoogleClickId($conversions['click_id']);
        $gc->setConversionValue($conversions['value']);

        $offlineConversionOperation = new OfflineConversionFeedOperation();
        $offlineConversionOperation->setOperand($gc);
        $offlineConversionOperation->setOperator(Operator::ADD);

        $this->offlineConversions[] = $conversions;
        $this->mutations[] = $offlineConversionOperation;

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
            try 
            {
                $result = ($this->service->call(OfflineConversionFeedService::class))->mutate([$mutate]);

                // array of "OfflineConversionFeed"
                $responseValues = $result->getValue();

                foreach($responseValues as $feed) {
                    $successResponse[$i] = $feed->getGoogleClickId();
                }
            }
            catch (ApiException $e) 
            {
                foreach($e->getErrors() as $err) 
                {
                    $reason = $err->getReason();
                    $arr    = $err->getFieldPathElements();
                    $index  = $arr[0]->getIndex() ?? null;
                    $click  = $this->offlineConversions[$index] ?? [];

                    $errorResponse[$i] = [
                        'click_id' => $click['click_id'],
                        'error' => $reason
                    ];
                }
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
