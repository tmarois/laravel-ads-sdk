<?php

namespace LaravelAds\Services\BingAds\Operations;

use LaravelAds\Services\BingAds\Service;

use Microsoft\BingAds\Auth\ServiceClientType;
use LaravelAds\Services\BingAds\Operations\Operation;
use Microsoft\BingAds\V13\CustomerManagement\UpdateCustomerRequest;
use Microsoft\BingAds\V13\CustomerManagement\GetCustomersInfoRequest;
use Microsoft\BingAds\V13\CustomerManagement\Customer as CustomerProxy;

class CustomerOperations extends Operation
{

    /**
     * __construct()
     *
     */
    public function __construct(Service $service = null) {
        $this->service = $service;
        $this->request = new CustomerProxy();
    }

    /**
     * sendRequest()
     *
     */
    protected function sendRequest()
    {
        $serviceCall = $this->service->call(ServiceClientType::CustomerManagementVersion13);

        try {
            $customer = $this->request();

            $request = new GetCustomersInfoRequest();
            $request->CustomerNameFilter = '';
            $request->TopN = 100;

            return $serviceCall->GetService()->GetCustomersInfo($request)->CustomersInfo->CustomerInfo[0] ?? null;
        } catch (\Exception $e) {
            print $serviceCall->GetService()->__getLastRequest()."\n";
            print $serviceCall->GetService()->__getLastResponse()."\n";
        }

        return (new CustomerProxy());
    }

    /**
     * save()
     *
     * TODO: this could probably be removed,
     *       but just incase someone calls this...
     */
    public function save($updateObject = true) {
        return $this;
    }
}
