# Laravel Ads SDK
For **Google Ads**, **Bing Ads** and **Facebook Ads** APIs.

This is a wrapper for connecting each ad source into your Laravel application using a single package. This SDK provides a simpler method API than the traditional SDKs, making it a lot easier to implement in your project.


## (1) Installation

Use [Composer](http://getcomposer.org/) to install package.

Run `composer require tmarois/laravel-ads-sdk:^1.0`

## (2) Laravel Config

Run `php artisan vendor:publish`

This will generate the config files located in your `/config` for `google-ads.php` and `bing-ads.php`.

## (3) Namespace
`use LaravelAds;`

Whenever you want to use this package, add this namespace at the top.

## (4) Services

Accessing `GoogleAds` or `BingAds` use the following: (be sure to include the client customer id)

```php
// calling the Google Ads Service and including the Customer Client Id
$googleAds = LaravelAds::service('GoogleAds')->with('CLIENT_ID');

// calling the Bing Ads Service and including the Customer Client Id
$bingAds = LaravelAds::service('BingAds')->with('CLIENT_ID');
```

# Google Ads API

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

Here are **pre-built methods** for retrieving reports

```php
// Get account level reports
$accountReport  = $googleAds->reports($dateFrom, $dateTo)->getAccountReport();

// get campaign level reports
$campaignReport = $googleAds->reports($dateFrom, $dateTo)->getCampaignReport();

// get adgroup level reports
$adgroupReprot  = $googleAds->reports($dateFrom, $dateTo)->getAdGroupReport();

```

**Learn more about reports and dimensions:**
* [Account Performance](https://developers.google.com/adwords/api/docs/appendix/reports/account-performance-report)
* [Campaign Performance](https://developers.google.com/adwords/api/docs/appendix/reports/campaign-performance-report)
* [Ad Group Performance](https://developers.google.com/adwords/api/docs/appendix/reports/adgroup-performance-report)

The fields are already set by default, however, if you want to set **your own fields**, you can do this:

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

Uses [BingAds-PHP-SDK](https://github.com/BingAds/BingAds-PHP-SDK) for [Bing Ads API](https://docs.microsoft.com/en-us/bingads/guides/get-started-php?view=bingads-12)

Coming Soon.

# Facebook Ads

Coming Soon. Looking for contributors.
