> Go back to the [README](README.md) for the LaravelAds SDK

# BingAds - Documentation

This uses the [BingAds-PHP-SDK](https://github.com/BingAds/BingAds-PHP-SDK) for the [Bing Ads API](https://docs.microsoft.com/en-us/bingads/guides/get-started-php?view=bingads-12).

### Getting Started

First, you need to use the service access for `Bing Ads` and add the Client Customer Id

```php
$bingAds = LaravelAds::bingAds()->with('CLIENT_ID');
```

#### Management
* [Fetching - All Campaigns](#fetch-all-campaigns)
* [Fetching - All Ad Groups](#fetch-all-ad-groups)
* [Management - Campaigns](#campaigns)
* [Management - Ad Groups](#ad-groups)

#### Reports
* [Account Performance](#account-reports)
* [Campaign Performance](#campaign-reports)
* [Ad Group Performance](#ad-group-reports)
* [Final URL Performance](#final-url-performance-report)
* [Search Term Performance](#search-term-performance-report)
* [Age Range Performance](#age-range-performance-report)
* [Gender Performance](#gender-performance-report)

## Fetching

If you don't want to learn how to handle the BingAds API request, here are **pre-built methods** to quickly get you going.

### Fetch All Campaigns

Fetching all campaigns within the account.

```php
$campaigns = $bingAds->fetch()->getCampaigns();
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
    [bid_strategy] => CPA
)
...
```


### Fetch All Ad Groups

Fetching all ad groups within the account.

```php
$adgroups  = $bingAds->fetch()->getAdGroups();
```

*Results: `getAdGroups()` (returns a [Laravel Collection](https://laravel.com/docs/5.7/collections) object, use `all()` for array)*

```
[0] => Array
(
    [id] => 0000000
    [name] => Ad Group Name
    [status] => ENABLED
    [campaign_id] => 1672496372
    [bid_strategy] => CPC
    [bid] => 0.65
)
...
```

## Reports

Here are the **pre-built methods** for retrieving reports.

### Account Reports

Pull **account-level** reports.

```php
$accountReport  = $bingAds->reports($dateFrom, $dateTo)
                          ->getAccountReport();
```

*Results: `getAccountReport()` (returns a [Laravel Collection](https://laravel.com/docs/5.7/collections) object, use `all()` for array)*

```
[0] => Array
(
    [date] => 2018-12-26
    [account_id] => 0000000
    [clicks] => 3131
    [impressions] => 73844
    [cost] => 1003.90
    [conversions] => 206
    [conversion_value] => 1039.95
)
...
```


### Campaign Reports

Pull **campaign-level** reports.

```php
$campaignReport  = $bingAds->reports($dateFrom, $dateTo)
                           ->getCampaignReport();
```

*Results: `getCampaignReport()` (returns a [Laravel Collection](https://laravel.com/docs/5.7/collections) object, use `all()` for array)*

```
[0] => Array
(
    [date] => 2018-12-26
    [account_id] => 00000000
    [campaign_name] => Campaign Name
    [campaign_id] => 00000000
    [clicks] => 2
    [impressions] => 267
    [cost] => 0.53
    [conversions] => 0
    [conversion_value] => 0.00
)
...
```

### Ad Group Reports

Pull **adgroup-level** reports.

```php
$adgroupReport  = $bingAds->reports($dateFrom, $dateTo)
                          ->getAdGroupReport();
```

*Results: `getAdGroupReport()` (returns a [Laravel Collection](https://laravel.com/docs/5.7/collections) object, use `all()` for array)*

```
[0] => Array
(
    [date] => 2018-12-26
    [account_id] => 000000
    [campaign_id] => 000000
    [campaign_name] => Campaign Name
    [ad_group_id] => 000000
    [ad_group_name] => Ad Group Name
    [clicks] => 684
    [impressions] => 6008
    [cost] => 290.13
    [conversions] => 87
    [conversion_value] => 466.05
)
...
```

### Final URL Performance Report

This report is **campaign-level** and uses [Destination URL Performance](https://docs.microsoft.com/en-us/bingads/reporting-service/destinationurlperformancereportrequest?view=bingads-12).

```php
$report  = $bingads->reports($dateFrom, $dateTo)
                     ->getFinalUrlReport();
```

*Results: `getFinalUrlReport()` (returns a [Laravel Collection](https://laravel.com/docs/5.7/collections) object, use `all()` for array)*

```
[0] => Array
(
    [date] => 2018-12-26
    [account_name] => Account Name
    [account_id] => 00000000
    [campaign_id] => 00000000
    [campaign_name] => Campaign Name
    [clicks] => 684
    [impressions] => 6008
    [cost] => 290.13
    [conversions] => 88
    [conversion_value] => 470.80
    [destination_url] => (no longer used, look at final url)
    [final_url] => https://your-final-url.com/landing-page
)
...
```

### Search Term Performance Report

This report is aggregated to **account-level** and uses [Search Query Performance](https://docs.microsoft.com/en-us/bingads/reporting-service/searchqueryperformancereportrequest?view=bingads-12).

```php
$report  = $bingads->reports($dateFrom, $dateTo)
                   ->getSearchTermReport();
```

*Results: `getSearchTermReport()` (returns a [Laravel Collection](https://laravel.com/docs/5.7/collections) object, use `all()` for array)*

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

This report is aggregated to **account-level** and uses [Age Gender Performance](https://docs.microsoft.com/en-us/bingads/reporting-service/agegenderaudiencereportcolumn?view=bingads-12).

```php
$report  = $bingads->reports($dateFrom, $dateTo)
                   ->getAgeRangeReport();
```

*Results: `getGenderReport()` (returns a [Laravel Collection](https://laravel.com/docs/5.7/collections) object, use `all()` for array)*

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

This report is aggregated to **account-level** and uses [Age Gender Performance](https://docs.microsoft.com/en-us/bingads/reporting-service/agegenderaudiencereportcolumn?view=bingads-12).

```php
$report  = $bingads->reports($dateFrom, $dateTo)
                   ->getGenderReport();
```

*Results: `getGenderReport()` (returns a [Laravel Collection](https://laravel.com/docs/5.7/collections) object, use `all()` for array)*

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
$campaign = $bingAds->campaign('CAMPAIGN_ID');
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
$bingAds->campaign('CAMPAIGN_ID')
          ->setName('My New Campaign Name')
          ->setStatus('ENABLED')
          ->setBudget(300)
          ->save();

// if you only want to grab the campaign name...
$campaignName = $bingAds->campaign('CAMPAIGN_ID')->getName();

// Get only the campaign status
$campaignStatus = $bingAds->campaign('CAMPAIGN_ID')->getStatus();

```

## Ad Groups

You can easily manage/view Ad Group settings by loading up the AdGroup service.

```php
// Campaign Id Is Required for Bing Ad Groups.
$adGroup = $bingAds->adGroup('ADGROUP_ID', 'CAMPAIGN_ID');
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
|`setId()`|SET the AdGroup id|
|`setName()`|SET the AdGroup name|
|`setStatus($status)`|SET AdGroup status (`ENABLED`,`PAUSED`)|
|`setBid($amount)`|SET AdGroup Bid (`FLOAT $amount`) *currently only the CPC/ECPC bid*|
|`save()`|Post your changes to the server|

### Example Usage

```php
// Rename the Ad Group, enable it, and then set the CPC bid to 0.22
$bingAds->adGroup('ADGROUP_ID', 'CAMPAIGN_ID')
          ->setName('My New Ad Group Name')
          ->setStatus('ENABLED')
          ->setBid(0.22)
          ->save();

// if you only want to grab the ad group name...
$adgroupName = $bingAds->adGroup('ADGROUP_ID', 'CAMPAIGN_ID')->getName();

// Get only the ad group status
$adgroupStatus = $bingAds->adGroup('ADGROUP_ID', 'CAMPAIGN_ID')->getStatus();

// Get only the ad group bid
$adGroupBid = $bingAds->adGroup('ADGROUP_ID', 'CAMPAIGN_ID')->getBid();

```

## Jump To:
* [Home](README.md)
* [GoogleAds - Getting Started](GoogleAds-SDK.md)
* [BingAds - Getting Started](BingAds-SDK.md)
