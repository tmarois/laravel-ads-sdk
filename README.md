# Laravel Ads SDK

[![Slack](http://timothymarois.com/a/slack-02.svg)](https://join.slack.com/t/basephp/shared_invite/enQtNDI0MzQyMDE0MDAwLWU3Nzg0Yjk4MjM0OWVmZDZjMjEyYWE2YjA1ODFhNjI2MzI3MjAyOTIyOTRkMmVlNWNhZWYzMTIwZDJlOWQ2ZTA)

For **Google Ads**, **Bing Ads** and **Facebook Ads** APIs.

This is a wrapper for connecting each ad source into your Laravel application. This SDK provides simpler and consistent methods across many ad source integrations than the traditional SDKs; ultimately making it a lot easier to implement in your projects. You shouldn't have to learn how to communicate and understand the responses to every API.

**The goal of this package is to provide you with one SDK to read them all.**

### (1) Installation

Use [Composer](http://getcomposer.org/) to install package.

Run `composer require grayscale/laravel-ads-sdk:^1.0`

### (2) Laravel Config

Run `php artisan vendor:publish`, then copy these to your `.env` and update with your credentials.

```
ADWORDS_DEVELOPER_TOKEN=""
ADWORDS_OAUTH2_CLIENT_ID=""
ADWORDS_OAUTH2_CLIENT_SECRET=""
ADWORDS_OAUTH2_REFRESH_TOKEN=""

BING_DEVELOPER_TOKEN=""
BING_CLIENT_ID=""
BING_CLIENT_SECRET=""
BING_REFRESH_TOKEN=""
```

### (3) For GoogleAds

*Follow the steps in the command line to generate a refresh token.*

Run `php artisan laravelads:token:generate --service=GoogleAds`

### (4) For BingAds

*Follow the steps in the command line to generate a refresh token.*

Run `php artisan laravelads:token:generate --service=BingAds`

Having Trouble? [Learn More](BingAds.md)

### (5) Usage

Accessing `GoogleAds` or `BingAds` services use the following:

```php
// The namespace to the Facade
use LaravelAds;

// calling the Google Ads Service and including the Customer Client Id
$googleAds = LaravelAds::googleAds()->with('CLIENT_ID');

// calling the Bing Ads Service and including the Customer Client Id
$bingAds = LaravelAds::bingAds()->with('CLIENT_ID');
```

# Google Ads

This uses the [googleads-php-lib](https://github.com/googleads/googleads-php-lib) SDK for the [Google Ads API](https://developers.google.com/adwords/api/docs/guides/start)

### Features

* [Fetch Campaigns/AdGroups](#fetching-information)
* [Reports: Account/Campaign/AdGroup](#reporting-data)
* [AdGroups](#adgroups)
* [AdGroup Operation: Change Bids](#operation-change-adgroup-bids)
* [AdGroup Operation: Update AdGroups](#operation-update-adgroup)

### Getting Started

First, you need to use the service access for `Google Ads` and add the Client Customer Id

```php
$googleAds = LaravelAds::googleAds()->with('CLIENT_ID');
```

### Fetching Information

If you don't want to learn how to handle the Google Ads API request, here are **pre-built methods** to quickly get you going.

```php
// get campaign information (returns as a Collection object)
$campaigns = $googleAds->fetch()->getCampaigns();

// get adgroup information (returns as a Collection object)
$adgroups  = $googleAds->fetch()->getAdGroups();

```

*Results: `getCampaigns()` (returns a [Laravel Collection](https://laravel.com/docs/5.7/collections) object, use `all()` for array)*

```
[0] => Array
(
    [id] => 000000000
    [name] => Campaign Name
    [status] => PAUSED
    [channel] => SEARCH
    [budget] => 5
)
...
```

*Results: `getAdGroups()` (returns a [Laravel Collection](https://laravel.com/docs/5.7/collections) object, use `all()` for array)*

```
[0] => Array
(
    [id] => 000000000000
    [name] => Ad Group Name
    [status] => ENABLED
    [campaign_id] => 0000000
    [campaign_name] => Campaign Name
    [bid_type] => MANUAL_CPC
    [bid] => 0.43
)
...
```

### Reporting Data

Here are the **pre-built methods** for retrieving reports

```php
// Get account level reports
$accountReport  = $googleAds->reports($dateFrom, $dateTo)
                            ->getAccountReport()
                            ->toArray();

// get campaign level reports
$campaignReport = $googleAds->reports($dateFrom, $dateTo)
                            ->getCampaignReport()
                            ->toArray();

// get adgroup level reports
$adgroupReport  = $googleAds->reports($dateFrom, $dateTo)
                            ->getAdGroupReport()
                            ->toArray();

```

Learn more about [Account Performance](https://developers.google.com/adwords/api/docs/appendix/reports/account-performance-report), [Campaign Performance](https://developers.google.com/adwords/api/docs/appendix/reports/campaign-performance-report), and [AdGroup Performance](https://developers.google.com/adwords/api/docs/appendix/reports/adgroup-performance-report) Reports.

The fields are already set by default, however, you can set **your own fields** too.

```php
// Setting fields will only output those fields in the response
$googleAds->reports($dateFrom, $dateTo)
          ->setFields(['AdGroupId','Conversions'])
          ->getCampaignReport();
```


*Results: `getAccountReport()`*

```
[0] => Array
(
    [Day] => 2018-12-13
    [Impressions] => 93013
    [Clicks] => 3282
    [Cost] => 1240.73
    [Conversions] => 290.00
    [Total conv. value] => 1367.25
    [All conv.] => 290.00
    [All conv. value] => 1367.25
)
...
```

*Results: `getCampaignReport()`*

```
[0] => Array
(
    [Day] => 2018-12-20
    [Advertising Channel] => Search
    [Campaign state] => paused
    [Campaign] => Campaign Name
    [Campaign ID] => 000000000
    [Impressions] => 90
    [Clicks] => 12
    [Cost] => 5.63
    [Conversions] => 0.00
    [Total conv. value] => 0.00
    [All conv.] => 0.00
    [All conv. value] => 0.00
)
...
```

*Results: `getAdGroupReport()`*

```
[0] => Array
(
    [Day] => 2018-12-09
    [Ad group ID] => 0000000
    [Ad group] => Ad Group Name
    [Campaign ID] => 00000000
    [Impressions] => 160
    [Clicks] => 4
    [Cost] => 0.93
    [Conversions] => 0.00
    [Total conv. value] => 0.00
    [All conv.] => 0.00
    [All conv. value] => 0.00
)
...
```


### AdGroups

|Method				|Description    |
|---				|---		    |
|`getId()`|GET AdGroup Id|
|`getName()`|GET AdGroup Name|
|`getStatus()`|GET AdGroup Status|
|`getAdGroupType()`|GET AdGroup Type|
|`getCampaignId()`|GET AdGroup Campaign id|
|`getBidType()`|GET AdGroup Bid Strategy Type|
|`get()`|GET AdGroup details from the server|
|`setName()`|SET the AdGroup name|
|`setBid()`|SET AdGroup Bid (name,type)|
|`setStatus()`|SET AdGroup status|
|`save()`|Posts changes to the server|

Grab the AdGroup details from the server and populate the object.

```php
$googleAds->adGroup('ADGROUPID');
```

### Operation: Change AdGroup Bids

Set the AdGroup bid; it will automatically set the bid based on the campaign bid strategy type.

```php
// This will change the cpc, cpm or cpa bid to 0.80
$googleAds->adGroup('ADGROUPID')
            ->setBid(0.80)
            ->save();

```

### Operation: Update AdGroup

You can chain AdGroup changes like below (even the bid changes), use `save()` when ready to post changes.

```php
// You can change the adgroup name and status
$googleAds->adGroup('ADGROUPID')
            ->setName('My New AdGroup Name')
            ->setStatus('PAUSED')
            ->save();

```

*Google AdGroup status must be equal to `ENABLED` or `PAUSED`.*


### Need More? Advanced Options

If the pre-built methods don't have what you're looking for, you may need to write your own methods to communicate with the AdWords Services directly. **You will need to understand how the [Google Ads API](https://developers.google.com/adwords/api/docs/reference/release-notes/v201809) and [Google Ads SDK](https://github.com/googleads/googleads-php-lib) work.**

```php
// You'll need to include the correct namespaces included in the Google Ads SDK
use Google\AdsApi\AdWords\v201809\cm\CampaignService;

// Start the initial step authenticating to a service
$googleAds = LaravelAds::googleAds()->with('CLIENT_ID');

// this communicates with the GoogleAds PHP LIB (returns AdWordsServices)
// return the service class so that you can manage the next step
// replace the "CampaignService::class" with the API service you want to use
$campaignService = $googleAds->service(CampaignService::class);

// ... write your logic here to understand the API response

```

If you've written a new feature that isn't included in this package, send a pull request :)

# Bing Ads

This uses the [BingAds-PHP-SDK](https://github.com/BingAds/BingAds-PHP-SDK) for the [Bing Ads API](https://docs.microsoft.com/en-us/bingads/guides/get-started-php?view=bingads-12)

> **NOTICE** – You will need to [Request Bing Ads API Access](https://advertise.bingads.microsoft.com/en-us/resources/bing-partner-program/request-bing-ads-api-access).

### Getting Started

**First**, you need to use the service access for `Bing Ads` and add the Client Customer Id

```php
$bingAds = LaravelAds::bingAds()->with('CLIENT_ID');
```

### Reporting Data

*Bing Report API is not designed with a fluent stream, it may be wonky, submit an issue if one arises.*

Here are the **pre-built methods** for retrieving reports. Learn more about [Bing Reports](https://docs.microsoft.com/en-us/bingads/reporting-service/reporting-service-reference?view=bingads-12).

```php
// Get account level reports
$accountReport  = $bingAds->reports($dateFrom, $dateTo)
                        ->getAccountReport()
                        ->toArray();

// Get campaign level reports
$campaignReport  = $bingAds->reports($dateFrom, $dateTo)
                        ->getCampaignReport()
                        ->toArray();

// Get ad group level reports
$adgroupReport  = $bingAds->reports($dateFrom, $dateTo)
                        ->getAdGroupReport()
                        ->toArray();

```

*Results: `getAccountReport()`*

```
[0] => Array
(
   [TimePeriod] => 2018-12-26
   [AccountId] => 00000000
   [Clicks] => 64262
   [Impressions] => 1421876
   [Spend] => 19433.56
   [Conversions] => 3505
   [Revenue] => 17161.25
)
...
```

*Results: `getCampaignReport()`*

```
[0] => Array
(
    [TimePeriod] => 2018-12-26
    [AccountId] => 0000000
    [CampaignName] => Campaign Name
    [CampaignId] => 000000000
    [CampaignStatus] => Active
    [Clicks] => 2
    [Impressions] => 267
    [Spend] => 0.53
    [Conversions] => 0
    [Revenue] => 0.00
)
...
```

*Results: `getAdGroupReport()`*

```
[0] => Array
(
    [TimePeriod] => 2018-12-26
    [AccountId] => 000000
    [CampaignId] => 00000000
    [AdGroupId] => 000000000000
    [AdGroupName] => Ad Group Name
    [Clicks] => 684
    [Impressions] => 6008
    [Spend] => 290.13
    [Conversions] => 82
    [Revenue] => 446.15
)
...
```



# Facebook Ads

Coming Soon. Looking for contributors.

# Contributions

We are actively looking for new contributors.

If you want to help, join the slack channel and/or submit pull requests.

# License

**Laravel Ads SDK** (This Package) is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
