# Laravel Ads SDK
For **Google Ads**, **Bing Ads** and **Facebook Ads** APIs.

This is a wrapper for connecting each ad source into your Laravel application using a single package. This SDK provides simpler and consistent methods across many ad source integrations than the traditional SDKs, making it a lot easier to implement in your project. You shouldn't have to learn every API, just use one package.


### (1) Installation

Use [Composer](http://getcomposer.org/) to install package.

Run `composer require tmarois/laravel-ads-sdk:^1.0`

### (2) Laravel Config

Run `php artisan vendor:publish`

This will generate the config files located in your `/config` for `google-ads.php` and `bing-ads.php`.

### (3) Usage

Accessing `GoogleAds` or `BingAds` services use the following:

```php
// The namespace to the Facade
use LaravelAds;

// calling the Google Ads Service and including the Customer Client Id
$googleAds = LaravelAds::service('GoogleAds')->with('CLIENT_ID');

// calling the Bing Ads Service and including the Customer Client Id
$bingAds = LaravelAds::service('BingAds')->with('CLIENT_ID');
```

# Google Ads

This uses the [googleads-php-lib](https://github.com/googleads/googleads-php-lib) SDK for the [Google Ads API](https://developers.google.com/adwords/api/docs/guides/start)

### Configuration

Copy this to the `.env` and update it with your credentials.

```
ADWORDS_DEVELOPER_TOKEN=""
ADWORDS_OAUTH2_CLIENT_ID=""
ADWORDS_OAUTH2_CLIENT_SECRET=""
ADWORDS_OAUTH2_REFRESH_TOKEN=""
```


### Getting Started

**First**, you need to use the service access for `GoogleAds` and add the Client Customer Id

```php
$googleAds = LaravelAds::service('GoogleAds')->with('CLIENT_ID');
```

### Fetching Information

If you don't want to learn how to handle the Google Ads API request, here are **pre-built methods** to quickly get you going.

```php
// get campaign information (returns as a Collection object)
$campaigns = $googleAds->fetch()->getCampaigns();

// get adgroup information (returns as a Collection object)
$adgroups  = $googleAds->fetch()->getAdGroups();

```

*Results: `getCampaigns()`*

```
[0] => Array
(
    [id] => 000000000
    [name] => Campaign Name
    [status] => PAUSED
    [channel] => SEARCH
    [budget] => 5
)
```

*Results: `getAdGroups()`*

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

**Learn more about performance reports:**
* [Account Performance](https://developers.google.com/adwords/api/docs/appendix/reports/account-performance-report)
* [Campaign Performance](https://developers.google.com/adwords/api/docs/appendix/reports/campaign-performance-report)
* [Ad Group Performance](https://developers.google.com/adwords/api/docs/appendix/reports/adgroup-performance-report)

The fields are already set by default, however, if you want to set **your own fields** too.

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
```

### Need More? Advanced Options

If the pre-built methods don't have what you're looking for, you may need to write your own methods to communicate with the AdWords Services directly. **You will need to understand how the [Google Ads API](https://developers.google.com/adwords/api/docs/reference/release-notes/v201809) and [Google Ads SDK](https://github.com/googleads/googleads-php-lib) work.**

```php
// You'll need to include the correct namespaces included in the Google Ads SDK
use Google\AdsApi\AdWords\v201809\cm\CampaignService;

// Start the initial step authenticating to a service
$googleAds = LaravelAds::service('GoogleAds')->with('CLIENT_ID');

// this communicates with the GoogleAds PHP LIB (returns AdWordsServices)
// return the service class so that you can manage the next step
$campaignService = $googleAds->service(CampaignService::class);

// ... write your logic here to understand the API response

```

If you've written a new feature that isn't included in this package, send a pull request :)

# Bing Ads

This uses the [BingAds-PHP-SDK](https://github.com/BingAds/BingAds-PHP-SDK) for the [Bing Ads API](https://docs.microsoft.com/en-us/bingads/guides/get-started-php?view=bingads-12)

### Configuration

Copy this to the `.env` and update it with your credentials.

```
BING_DEVELOPER_TOKEN=""
BING_CLIENT_ID=""
BING_CLIENT_SECRET=""
BING_REFRESH_TOKEN=""
```

### Getting Started

**First**, you need to use the service access for `BingAds` and add the Client Customer Id

```php
$bingAds = LaravelAds::service('BingAds')->with('CLIENT_ID');
```

### Reporting Data

*NOTE: Bing Report API is not designed with a fluent stream, it may be wonky, submit an issue if one arises.*

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
   [AccountId] => 00000000
   [Clicks] => 64262
   [Impressions] => 1421876
   [Spend] => 19433.56
   [Conversions] => 3505
   [Revenue] => 17161.25
)
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
```



# Facebook Ads

Coming Soon. Looking for contributors.
