> Go back to the [README](README.md) for the LaravelAds SDK

# GoogleAds - Documentation

This uses the [googleads-php-lib](https://github.com/googleads/googleads-php-lib) SDK for the [Google Ads API](https://developers.google.com/adwords/api/docs/guides/start).

### Getting Started

First, you need to use the service access for `Google Ads` and add the Client Customer Id

```php
$googleAds = LaravelAds::googleAds()->with('CLIENT_ID');
```

#### Management
* [Fetching - All Campaigns](#fetch-all-campaigns)
* [Fetching - All Ad Groups](#fetch-all-ad-groups)
* [Management - Campaigns](#campaigns)
* [Management - Ad Groups](#ad-groups)
* [Offline Conversion Import](#offline-conversion-import)
* [Manual Configuration](#manual-configuration)
* [Advanced Options](#need-more-advanced-options)

#### Reports
* [Account Performance](#account-reports)
* [Campaign Performance](#campaign-reports)
* [Ad Group Performance](#ad-group-reports)
* [Final URL Performance](#final-url-performance-report)
* [Placement Domain Performance](#placement-domain-performance-report)
* [Placement URL Performance](#placement-url-performance-report)
* [Search Term Performance](#search-term-performance-report)
* [Age Range Performance](#age-range-performance-report)
* [Gender Performance](#gender-performance-report)

---------------------------------------------------------

## Fetching

If you don't want to learn how to handle the Google Ads API request, here are **pre-built methods** to quickly get you going.

### Fetch All Campaigns

Fetching all campaigns within the account.

```php
$campaigns = $googleAds->fetch()->getCampaigns();
```

*Results: `getCampaigns()` (returns a [Laravel Collection](https://laravel.com/docs/collections) object, use `all()` for array)*

```
[0] => Array
(
    [id] => 000000000
    [name] => Campaign Name
    [status] => PAUSED
    [channel] => SEARCH
    [budget] => 5
    [bid_strategy] => CPA
)
...
```


### Fetch All Ad Groups

Fetching all ad groups within the account.

```php
$adgroups  = $googleAds->fetch()->getAdGroups();
```

*Results: `getAdGroups()` (returns a [Laravel Collection](https://laravel.com/docs/collections) object, use `all()` for array)*

```
[0] => Array
(
    [id] => 0000000
    [name] => Ad Group Name
    [status] => ENABLED
    [campaign_id] => 1672496372
    [type] => SEARCH_STANDARD
    [bid_strategy] => CPC
    [bid] => 0.65
)
...
```

## Reports

Here are the **pre-built methods** for retrieving reports.

### Account Reports

Pull **account-level** reports. This report uses [Account Performance](https://developers.google.com/adwords/api/docs/appendix/reports/account-performance-report).

```php
$accountReport  = $googleAds->reports($dateFrom, $dateTo)
                            ->getAccountReport();
```

*Results: `getAccountReport()` (returns a [Laravel Collection](https://laravel.com/docs/collections) object, use `all()` for array)*

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


### Campaign Reports

Pull **campaign-level** reports. This report uses [Campaign Performance](https://developers.google.com/adwords/api/docs/appendix/reports/campaign-performance-report).

```php
$campaignReport  = $googleAds->reports($dateFrom, $dateTo)
                             ->getCampaignReport();
```

*Results: `getCampaignReport()` (returns a [Laravel Collection](https://laravel.com/docs/collections) object, use `all()` for array)*

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

### Ad Group Reports

Pull **adgroup-level** reports. This report uses [AdGroup Performance](https://developers.google.com/adwords/api/docs/appendix/reports/adgroup-performance-report).

```php
$adgroupReport  = $googleAds->reports($dateFrom, $dateTo)
                            ->getAdGroupReport();
```

*Results: `getAdGroupReport()` (returns a [Laravel Collection](https://laravel.com/docs/collections) object, use `all()` for array)*

```
[0] => Array
(
    [date] => 2018-12-18
    [ad_group_id] => 000000
    [ad_group_name] => Ad Group Name
    [campaign_id] => 0000000
    [campaign_name] => Campaign Name
    [impressions] => 2
    [clicks] => 0
    [cost] => 0
    [conversions] => 0.00
    [conversion_value] => 0.00
)
...
```

### Final URL Performance Report

This report is **campaign-level** and uses [Final URL Performance](https://developers.google.com/adwords/api/docs/appendix/reports/final-url-report).

```php
$report  = $googleAds->reports($dateFrom, $dateTo)
                     ->getFinalUrlReport();
```

*Results: `getFinalUrlReport()` (returns a [Laravel Collection](https://laravel.com/docs/collections) object, use `all()` for array)*

```
[0] => Array
(
    [date] => 2019-01-23
    [campaign_id] => 00000000
    [campaign_name] => Campaign Name
    [impressions] => 3
    [clicks] => 0
    [cost] => 0
    [conversions] => 0.00
    [conversion_value] => 0.00
    [final_url] => https://your-final-url.com/landing-page
)
...
```

### Placement Domain Performance Report

This report is aggregated to **account-level** and uses [Placement Performance](https://developers.google.com/adwords/api/docs/appendix/reports/placement-performance-report).

```php
$report  = $googleAds->reports($dateFrom, $dateTo)
                     ->getPlacementReport();
```

*Results: `getPlacementReport()` (returns a [Laravel Collection](https://laravel.com/docs/collections) object, use `all()` for array)*

```
// the key of the array is also the placement domain
// this ensures a unique placement aggregation

[example.com] => Array
(
    [placement] => example.com
    [impressions] => 9
    [clicks] => 0
    [cost] => 0
    [conversions] => 0.00
    [conversion_value] => 0.00
)
...
```

### Placement URL Performance Report

This report is aggregated to **account-level** and uses [URL Performance](https://developers.google.com/adwords/api/docs/appendix/reports/url-performance-report).

```php
$report  = $googleAds->reports($dateFrom, $dateTo)
                     ->getPlacementUrlReport();
```

*Results: `getPlacementUrlReport()` (returns a [Laravel Collection](https://laravel.com/docs/collections) object, use `all()` for array)*

```
// the key of the array is also the placement URL
// this ensures a unique placement URL aggregation

[example.com/page/path.html] => Array
(
    [placement] => example.com/page/path.html
    [impressions] => 9
    [clicks] => 0
    [cost] => 0
    [conversions] => 0.00
    [conversion_value] => 0.00
)
...
```


### Search Term Performance Report

This report is aggregated to **account-level** and uses [Search Query Performance](https://developers.google.com/adwords/api/docs/appendix/reports/search-query-performance-report).

```php
$report  = $googleAds->reports($dateFrom, $dateTo)
                     ->getSearchTermReport();
```

*Results: `getSearchTermReport()` (returns a [Laravel Collection](https://laravel.com/docs/collections) object, use `all()` for array)*

```
// the key of the array is also the search term
// this ensures a unique search term for aggregation

[food in boston ma] => Array
(
    [search_term] => food in boston ma
    [impressions] => 1
    [clicks] => 1
    [cost] => 0.47
    [conversions] => 0.00
    [conversion_value] => 0.00
)
...
```

### Age Range Performance Report

This report is aggregated to **account-level** and uses [Age Range Performance](https://developers.google.com/adwords/api/docs/appendix/reports/age-range-performance-report).

```php
$report  = $googleAds->reports($dateFrom, $dateTo)
                     ->getAgeRangeReport();
```

*Results: `getGenderReport()` (returns a [Laravel Collection](https://laravel.com/docs/collections) object, use `all()` for array)*

```
// the key of the array is also the age range
// this ensures a unique age range for aggregation
// Age (Valid age ranges are 18-24, 25-34, 35-44, 45-54, 55-64, 65 or more, and Unknown.)

[18-24] => Array
(
   [age_range] => 18-24
   [impressions] => 12517
   [clicks] => 203
   [cost] => 58.65
   [conversions] => 11
   [conversion_value] => 51.25
)
...
```

### Gender Performance Report

This report is aggregated to **account-level** and uses [Gender Performance](https://developers.google.com/adwords/api/docs/appendix/reports/gender-performance-report).

```php
$report  = $googleAds->reports($dateFrom, $dateTo)
                     ->getGenderReport();
```

*Results: `getGenderReport()` (returns a [Laravel Collection](https://laravel.com/docs/collections) object, use `all()` for array)*

```
// the key of the array is also the gender
// this ensures a unique gender for aggregation
// Genders (Male, Female)

[Female] => Array
(
    [gender] => Female
    [impressions] => 16045
    [clicks] => 411
    [cost] => 162.28
    [conversions] => 18
    [conversion_value] => 88
)
...
```


## Campaigns

You can easily manage/view campaign settings by loading up the campaign service.

```php
$campaign = $googleAds->campaign('CAMPAIGN_ID');
```

**Available methods** from the `Campaign` object. (the `SET` methods are chainable, see example)

|Method				|Description    |
|---				|---		    |
|`getId()`|GET the Campaign id|
|`getName()`|GET the Campaign name|
|`getStatus()`|GET the Campaign Status (`ENABLED`,`PAUSED`,`DELETED`)|
|`getBudget()`|GET the Campaign budget|
|`getBudgetDelivery()`|GET the Campaign budget delivery (`ACCELERATED`,`STANDARD`)|
|`getBidStrategy()`|GET the Campaign bid strategy (`CPA`,`CPC`,`ECPC`)|
|`getTargetCpa()`|GET the Campaign Target CPA (if one exists)|
|`setId()`|SET the Campaign id|
|`setName()`|SET the Campaign name|
|`setStatus($status)`|SET Campaign status (`ENABLED`,`PAUSED`)|
|`setBudget($amount)`|SET Campaign Budget (`FLOAT $amount`)|
|`setTargetCpa($amount)`|SET Campaign Target CPA (`FLOAT $amount`)|
|`save()`|Post your changes to the server|


### Example Usage

```php
// Rename the campaign, enable it, and then set the budget to $300
$googleAds->campaign('CAMPAIGN_ID')
          ->setName('My New Campaign Name')
          ->setStatus('ENABLED')
          ->setBudget(300)
          ->save();

// if you only want to grab the campaign name...
$campaignName = $googleAds->campaign('CAMPAIGN_ID')->getName();

// Get only the campaign status
$campaignStatus = $googleAds->campaign('CAMPAIGN_ID')->getStatus();

```


## Ad Groups

You can easily manage/view Ad Group settings by loading up the AdGroup service.

```php
$adGroup = $googleAds->adGroup('ADGROUP_ID');
```

**Available methods** from the `AdGroup` object. (the `SET` methods are chainable, see example)

|Method				|Description    |
|---				|---		    |
|`getId()`|GET the AdGroup id|
|`getName()`|GET the AdGroup name|
|`getCampaignId()`|GET the AdGroup Campaign Id|
|`getStatus()`|GET the AdGroup Status (`ENABLED`,`PAUSED`,`DELETED`)|
|`getBidStrategy()`|GET the AdGroup bid strategy (`CPA`,`CPC`,`ECPC`)|
|`getBid()`|GET the AdGroup Bid Amount|
|`getType()`|GET the AdGroup Type|
|`setId()`|SET the AdGroup id|
|`setName()`|SET the AdGroup name|
|`setStatus($status)`|SET AdGroup status (`ENABLED`,`PAUSED`)|
|`setBid($amount)`|SET AdGroup Bid (`FLOAT $amount`) *currently only the CPC/ECPC bid*|
|`save()`|Post your changes to the server|

### Example Usage

```php
// Rename the Ad Group, enable it, and then set the CPC bid to 0.22
$googleAds->adGroup('ADGROUP_ID')
          ->setName('My New Ad Group Name')
          ->setStatus('ENABLED')
          ->setBid(0.22)
          ->save();

// if you only want to grab the ad group name...
$adgroupName = $googleAds->adGroup('ADGROUP_ID')->getName();

// Get only the ad group status
$adgroupStatus = $googleAds->adGroup('ADGROUP_ID')->getStatus();

// Get only the ad group bid
$adGroupBid = $googleAds->adGroup('ADGROUP_ID')->getBid();

```

## Offline Conversion Import

You can import offline conversions using this simple method. Uses [OfflineConversionFeedService](https://developers.google.com/adwords/api/docs/reference/v201809/OfflineConversionFeedService)

```php
// Can chain and add() as many as you wish
$conversionImport = $googleAds->offlineConversionImport()
    ->add([
        'click_id' => 'CjwKCAjwzJjrBRBvEiwA867byorDPQ2K0zO_8bXuJ7SeEs',
        'value' => 0,
        'name' => 'CONVERSION NAME',
        'time' => 'DATETIME TIMEZONE'
    ])
    ->add([
        'click_id' => 'CjwKCAjwzJjrBRBvEiwA867',
        'value' => 0,
        'name' => 'CONVERSION NAME',
        'time' => 'DATETIME TIMEZONE'
    ]);

// when read, begin the upload
// Response will return an array of [success] and [errors]
$response = $conversionImport->upload();

// passing true will return a more detail array of [success] and [errors] for each upload
$response = $conversionImport->upload(true);

```

*Note: `time` must include the timezone. "20190828 200112 America/New_York" (format "Ymd His timezone")*

**Methods:**

|Method|Description|
|---|---|
|`add( single array )`|Adding a single conversion
|`addBulk( multi-array )`|Adding an array of single conversions
|`upload()`|Imports the conversions to Google (pass `true` or `false` as arg to return more detail success array)

**Response:**

The array response is both `success` and `errors`, errors will include [Google Error Codes](https://developers.google.com/adwords/api/docs/reference/v201809/OfflineConversionFeedService.OfflineConversionError.Reason)

*Note: Click Ids that are success will not appear in the errors array and vise-versa.*

```
Array
(
    [errors] => Array
    (
        [0] => Array
        (
            [click_id] => CjwKCAjwzJjrBRBvEiwA867
            [error] => TOO_RECENT_CLICK
        )
    )
    [success] => Array
    (
        [1] => CjwKCAjwzJjrBRBvEiwA867byorDPQ2K0zO_8bXuJ7SeEs
    )
)
...
```


## Manual Configuration

By default, the configuration will always look at the `/config/google-ads.php`, however, you can override that by injecting your own config into the google ads service object.

**You only need to use this if you WANT to override the config, otherwise the config file will work in most cases.**

```php
$googleAds = LaravelAds::googleAds();
$googleAds->configuration([
    'ADWORDS' => [
        'developerToken' => ''
    ],
    'ADWORDS_REPORTING' => [

    ],
    'OAUTH2' => [
        'clientId' => '',
        'clientSecret' => '',
        'refreshToken' => '',
    ],
    'LOGGING' => [
        'soapLogLevel' => 'ERROR',
        'reportDownloaderLogLevel' => 'ERROR'
    ]
]);

$googleAds = $googleAds->with('ACCOUNT_ID');

// after the config is set above, now you can use the SDK as you normally do...
// $report = $googleAds->reports('2020-01-01', '2020-01-05')->getAccountReport();

```


## Need More? Advanced Options

If the pre-built methods don't have what you're looking for, you may need to write your own methods to communicate with the AdWords Services directly. **You will need to understand how the [Google Ads API](https://developers.google.com/adwords/api/docs/reference/release-notes/v201809) and [Google Ads SDK](https://github.com/googleads/googleads-php-lib) work.**

```php
// You'll need to include the correct namespaces included in the Google Ads SDK
use Google\AdsApi\AdWords\v201809\cm\CampaignService;

// Start the initial step authenticating to a service
$googleAds = LaravelAds::googleAds()->with('CLIENT_ID');

// this communicates with the GoogleAds PHP LIB (returns AdWordsServices)
// return the service class so that you can manage the next step
// replace the "CampaignService::class" with the API service you want to use
$campaignService = $googleAds->call(CampaignService::class);

// ... write your logic here to understand the API response

```

## Jump To:
* [Home](README.md)
* [GoogleAds - Getting Started](GoogleAds-SDK.md)
* [BingAds - Getting Started](BingAds-SDK.md)
* [FacebookAds - Getting Started](FacebookAds-SDK.md)