# Laravel Ads SDK

[![Slack](http://timothymarois.com/a/slack-02.svg)](https://join.slack.com/t/basephp/shared_invite/enQtNDI0MzQyMDE0MDAwLWU3Nzg0Yjk4MjM0OWVmZDZjMjEyYWE2YjA1ODFhNjI2MzI3MjAyOTIyOTRkMmVlNWNhZWYzMTIwZDJlOWQ2ZTA)

For **Google Ads**, **Bing Ads** and **Facebook Ads** APIs.

This is a wrapper for connecting each ad source into your Laravel application. This SDK provides simpler and consistent methods across many ad source integrations than the traditional SDKs; ultimately making it a lot easier to implement in your projects. You shouldn't have to learn how to communicate and understand the responses to every API.

**The goal of this package is to provide you with one SDK to manage them all.**

### (1) Installation

Use [Composer](http://getcomposer.org/) to install package.

Run `composer require grayscale/laravel-ads-sdk:^1.1`

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
    [bid_type] => TARGET_CPA
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
    [bid_type] => MANUAL_CPC
    [bid] => 0.43
)
...
```

### Reporting Data

Here are the **pre-built methods** for retrieving reports.

```php
// Get account level reports
$accountReport  = $googleAds->reports($dateFrom, $dateTo)
                            ->getAccountReport();

// get campaign level reports
$campaignReport = $googleAds->reports($dateFrom, $dateTo)
                            ->getCampaignReport();

// get adgroup level reports
$adgroupReport  = $googleAds->reports($dateFrom, $dateTo)
                            ->getAdGroupReport()

```

Learn more about [Account Performance](https://developers.google.com/adwords/api/docs/appendix/reports/account-performance-report), [Campaign Performance](https://developers.google.com/adwords/api/docs/appendix/reports/campaign-performance-report), and [AdGroup Performance](https://developers.google.com/adwords/api/docs/appendix/reports/adgroup-performance-report) Reports.

The fields are already set by default, however, you can set **your own fields** too.

```php
// Setting fields will only output those fields in the response
$googleAds->reports($dateFrom, $dateTo)
          ->setFields(['AdGroupId','Conversions'])
          ->getCampaignReport();
```


*Results: `getAccountReport()` (returns a [Laravel Collection](https://laravel.com/docs/5.7/collections) object, use `all()` for array)*

```
[0] => Array
(
    [date] => 2018-12-16
    [impressions] => 3286
    [clicks] => 294
    [cost] => 724
    [conversions] => 9.00
    [conversion_value] => 15.75
)
...
```

*Results: `getCampaignReport()` (returns a [Laravel Collection](https://laravel.com/docs/5.7/collections) object, use `all()` for array)*

```
[0] => Array
(
    [date] => 2018-12-15
    [channel] => Search
    [campaign_status] => paused
    [campaign_name] => Campaign Name
    [campaign_id] => 000000
    [impressions] => 2
    [clicks] => 0
    [cost] => 0
    [conversions] => 0.00
    [conversion_value] => 0.00
)
...
```

*Results: `getAdGroupReport()` (returns a [Laravel Collection](https://laravel.com/docs/5.7/collections) object, use `all()` for array)*

```
[0] => Array
(
    [date] => 2018-12-18
    [ad_group_id] => 000000
    [ad_group_name] => Ad Group Name
    [campaign_id] => 0000000
    [impressions] => 2
    [clicks] => 0
    [cost] => 0
    [conversions] => 0.00
    [conversion_value] => 0.00
)
...
```


### AdGroups

Use `get()` to grab AdGroup details from the server and populate the object. Returns `AdGroupResponse` object.

```php
// Ad Group ID is required
$adgroup = $googleAds->adGroup()
                     ->setId('ADGROUP_ID')
                     ->get();
```

These methods are available from `AdGroupResponse`

|Method				|Description    |
|---				|---		    |
|`getId()`|GET AdGroup Id|
|`getName()`|GET AdGroup Name|
|`getStatus()`|GET AdGroup Status|
|`getBidType()`|GET AdGroup Bid Strategy Type|
|`getBid()`|GET AdGroup bid amount|
|`getCampaignId()`|GET Campaign Id|
|`get()`|GET AdGroup details from the server|

To create or edit AdGroups, load a blank `AdGroup` from `AdGroupRequest` object, use `save()` to push changes.

```php
// setting the campaign name and bid
// include the Id and Campaign Id when changing existing ad groups
$adgroup = $googleAds->adGroup()
                     ->setId('ADGROUP_ID')
                     ->setBid(0.5)
                     ->setName('My AdGroup Name')
                     ->save();

```

These methods are available from `AdGroupRequest`

|Method				|Description    |
|---				|---		    |
|`setId()`|SET the AdGroup id|
|`setCampaignId()`|SET the Campaign id|
|`setName()`|SET the AdGroup name|
|`setBid()`|SET AdGroup Bid|
|`setStatus()`|SET AdGroup status|
|`save()`|Posts changes to the server|


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

First, you need to use the service access for `Bing Ads` and add the Client Customer Id

```php
$bingAds = LaravelAds::bingAds()->with('CLIENT_ID');
```

### Fetching Information

If you don't want to learn how to handle the Bing Ads API request, here are **pre-built methods** to quickly get you going.

```php
// get campaign information (returns as a Collection object)
$campaigns = $bingAds->fetch()->getCampaigns();

// get adgroup information (returns as a Collection object)
$adgroups  = $bingAds->fetch()->getAdGroups();

```

*Results: `getCampaigns()` (returns a [Laravel Collection](https://laravel.com/docs/5.7/collections) object, use `all()` for array)*

```
[0] => Array
(
    [id] => 000000000
    [name] => Campaign Name
    [status] => Active
    [channel] => Search
    [budget] => 15
    [bid_type] => TargetCpa
)
...
```

*Results: `getAdGroups()` (returns a [Laravel Collection](https://laravel.com/docs/5.7/collections) object, use `all()` for array)*

```
[0] => Array
(
   [id] => 000000000
   [campaign_id] => 000000
   [name] => Ad Group Name
   [status] => Active
   [bid] => 0.28
   [bid_type] => TargetCpa
)
...
```

### Reporting Data

*Bing Report API is not designed with a fluent stream, it may be wonky, submit an issue if one arises.*

Here are the **pre-built methods** for retrieving reports. Learn more about [Bing Reports](https://docs.microsoft.com/en-us/bingads/reporting-service/reporting-service-reference?view=bingads-12).

```php
// Get account level reports
$accountReport  = $bingAds->reports($dateFrom, $dateTo)
                        ->getAccountReport();

// Get campaign level reports
$campaignReport  = $bingAds->reports($dateFrom, $dateTo)
                        ->getCampaignReport();

// Get ad group level reports
$adgroupReport  = $bingAds->reports($dateFrom, $dateTo)
                        ->getAdGroupReport();

```

*Results: `getAccountReport()` (returns a [Laravel Collection](https://laravel.com/docs/5.7/collections) object, use `all()` for array)*

```
[0] => Array
(
    [date] => 2018-12-26
    [account_id] => 000000
    [clicks] => 3131
    [impressions] => 73844
    [cost] => 1003.90
    [conversions] => 200
    [conversion_value] => 1017.45
)
...
```

*Results: `getCampaignReport()` (returns a [Laravel Collection](https://laravel.com/docs/5.7/collections) object, use `all()` for array)*

```
[0] => Array
(
   [date] => 2018-12-26
   [account_id] => 000000
   [campaign_name] => Campaign Name
   [campaign_id] => 00000
   [campaign_status] => Active
   [clicks] => 2
   [impressions] => 267
   [cost] => 0.53
   [conversions] => 0
   [conversion_value] => 0.00
)
...
```

*Results: `getAdGroupReport()` (returns a [Laravel Collection](https://laravel.com/docs/5.7/collections) object, use `all()` for array)*

```
[0] => Array
(
    [date] => 2018-12-26
    [account_id] => 00000000
    [campaign_id] => 0000000
    [ad_group_id] => 0000000
    [ad_group_name] => Ad Group Name
    [clicks] => 684
    [impressions] => 6008
    [cost] => 290.13
    [conversions] => 83
    [conversion_value] => 451.05
)
...
```

### AdGroups

Use `get()` to grab AdGroup details from the server and populate the object. Returns `AdGroupResponse` object.

```php
// Ad Group ID and Campaign ID is required
$adgroup = $bingAds->adGroup()
                   ->setId('ADGROUP_ID')
                   ->setCampaignId('CAMPAIGN_ID')
                   ->get();
```

These methods are available from `AdGroupResponse`

|Method				|Description    |
|---				|---		    |
|`getId()`|GET AdGroup Id|
|`getName()`|GET AdGroup Name|
|`getStatus()`|GET AdGroup Status|
|`getBidType()`|GET AdGroup Bid Strategy Type|
|`getBid()`|GET AdGroup bid amount|
|`getCampaignId()`|GET Campaign Id|
|`get()`|GET AdGroup details from the server|

To create or edit AdGroups load a blank `AdGroup` from `AdGroupRequest` object, use `save()` to push changes.

```php
// setting the campaign name and bid
// include the Id and Campaign Id when changing existing ad groups
$adgroup = $bingAds->adGroup()
                   ->setId('ADGROUP_ID')
                   ->setCampaignId('CAMPAIGN_ID')
                   ->setBid(0.5)
                   ->setName('My AdGroup Name')
                   ->save();

```

These methods are available from `AdGroupRequest`

|Method				|Description    |
|---				|---		    |
|`setId()`|SET the AdGroup id|
|`setCampaignId()`|SET the Campaign id|
|`setName()`|SET the AdGroup name|
|`setBid()`|SET AdGroup Bid|
|`setStatus()`|SET AdGroup status|
|`save()`|Posts changes to the server|


# Facebook Ads

This uses the [facebook-php-business-sdk](https://github.com/facebook/facebook-php-business-sdk) for [Facebook Marketing API](https://developers.facebook.com/docs/marketing-apis)

Coming Soon. Looking for contributors.

# Contributions

We are actively looking for new contributors.

If you want to help, join the slack channel and/or submit pull requests.

# License

**Laravel Ads SDK** (This Package) is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
