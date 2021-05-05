<?php

namespace LaravelAds\Services\GoogleAds\Operations;

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
     * Add multiple conversions to the offline array
     *
     * @param array $conversions
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
     * Add a single conversion to the offline array
     * The conversion array should have keys of
     *
     *    name, time, click_id, value
     *
     *
     * @param array $conversion
     * @return OfflineConversions
     */
    public function add(array $conversion = [])
    {
        $gc = new OfflineConversionFeed();
        $gc->setConversionName($conversion['name']);
        $gc->setConversionTime($conversion['time']);
        $gc->setGoogleClickId($conversion['click_id']);
        $gc->setConversionValue($conversion['value']);

        $offlineConversionOperation = new OfflineConversionFeedOperation();
        $offlineConversionOperation->setOperand($gc);
        $offlineConversionOperation->setOperator(Operator::ADD);

        $this->offlineConversions[] = $conversion;
        $this->mutations[] = $offlineConversionOperation;

        return $this;
    }

    /**
     * upload()
     *
     * This method will upload offline converions
     * and return the success and errors of each id
     *
     * https://github.com/googleads/googleads-php-lib/blob/cec475ce83f8cdb923cfc08d9053b48769c0e64a/src/Google/AdsApi/AdWords/v201809/cm/OfflineConversionFeed.php
     *
     */
    public function upload($outputValue = false)
    {
        $errorResponse = [];
        $successResponse = [];

        foreach ($this->mutations as $i => $mutate) {
            $click = $this->offlineConversions[$i] ?? [];

            try {
                $result = ($this->service->call(OfflineConversionFeedService::class))->mutate([$mutate]);
                $responseValues = $result->getValue();
                foreach ($responseValues as $feed) {
                    if ($outputValue == true) {
                        // $successResponse[] = [
                        //     'click_id' => $feed->getGoogleClickId(),
                        //     'value' => $feed->getConversionValue()
                        // ];

                        $successResponse[] = [
                            'name' => $click['name'],
                            'time' => $click['time'],
                            'click_id' => $click['click_id'],
                            'value' => $click['value'] ?? 0
                        ];
                    } else {
                        $successResponse[] = $feed->getGoogleClickId();
                    }
                }
            } catch (ApiException $e) {
                foreach ($e->getErrors() as $err) {
                    $reason = $err->getReason();
                    $errorResponse[] = [
                        'name' => $click['name'],
                        'time' => $click['time'],
                        'click_id' => $click['click_id'],
                        'value' => $click['value'] ?? 0,
                        'error' => $reason
                    ];
                }
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
