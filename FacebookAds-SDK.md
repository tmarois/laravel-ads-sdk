> Go back to the [README](README.md) for the LaravelAds SDK

# FacebookAds - Documentation

This uses the [facebook-php-business-sdk](https://github.com/facebook/facebook-php-business-sdk) for [Facebook Marketing API](https://developers.facebook.com/docs/marketing-apis).

### Getting Started

First, you'll need the ads account id, this can be found within your ads manager. You can also locate this in the facebook ads manager url `act=ACCOUNT_ID`.

```php
$facebookAds = LaravelAds::facebookAds()->with('ADS_ACCOUNT_ID');
```

#### Management
* [Fetching - Campaigns](#fetch-campaigns)
* [Fetching - Ad Groups](#fetch-ad-groups)
#### Reports
* [Account Performance](#account-reports)
* [Campaign Performance](#campaign-reports)
* [Ad Group Performance](#ad-group-reports)

---------------------------------------------------------

## Fetching

If you don't want to learn how to handle the Facebook Ads API request, here are **pre-built methods** to quickly get you going.

### Fetch Campaigns

Fetching all campaigns within the account.

```php
$campaigns = $facebookAds->fetch()->getCampaigns();
```

*Results: `getCampaigns()` (returns a [Laravel Collection](https://laravel.com/docs/collections) object, use `all()` for array)*

```
[0] => Array
(
    [account_id] => 000000000000000000
    [id] => 000000000000000000
    [name] => Campaign Name
    [status] => PAUSED
)
...
```

#### Choosing Fields

You can also choose which fields (columns) you wish to return, `getCampaigns(Array $fields)`. Here are the [Allowed Fields](https://github.com/facebook/facebook-php-business-sdk/blob/master/src/FacebookAds/Object/Fields/CampaignFields.php)

```php
use FacebookAds\Object\Fields\CampaignFields;

// This is the default field list
// https://github.com/facebook/facebook-php-business-sdk/blob/master/src/FacebookAds/Object/Fields/CampaignFields.php
$fields = [
    CampaignFields::ACCOUNT_ID,
    CampaignFields::ID,
    CampaignFields::NAME,
    CampaignFields::STATUS,
    CampaignFields::BID_STRATEGY,
    CampaignFields::DAILY_BUDGET
];

// (OPTIONAL)
// You can pass in params to filter the response
// Filter by status: this will get all PAUSED campaigns
// https://developers.facebook.com/docs/marketing-api/reference/ad-account/campaigns/
$params = ['effective_status'=>['PAUSED']];

$campaigns = $facebookAds->fetch()->getCampaigns($fields, $params);

```

### Fetch Ad Groups

Fetching all ad groups (ad sets) within the account. You can also use `->getAdGroups()`, will be the same as example below. 

```php
$adgroups  = $facebookAds->fetch()->getAdSets();
```

*Results: `getAdSets()` (returns a [Laravel Collection](https://laravel.com/docs/collections) object, use `all()` for array)*

```
[0] => Array
(
    [account_id] => 000000000000000000
    [campaign_id] => 000000000000000000
    [id] => 000000000000000000
    [name] => Ad Set Name
    [status] => ACTIVE
    [daily_budget] => 2000
    [bid_amount] => 140
    [bid_strategy] => LOWEST_COST_WITH_BID_CAP
)
...
```

#### Choosing Fields

You can also choose which fields (columns) you wish to return, `getAdSets(Array $fields)`. Here are the [Allowed Fields](https://github.com/facebook/facebook-php-business-sdk/blob/master/src/FacebookAds/Object/Fields/AdSetFields.php)

```php
use FacebookAds\Object\Fields\AdSetFields;

// This is the default field list
// https://github.com/facebook/facebook-php-business-sdk/blob/master/src/FacebookAds/Object/Fields/AdSetFields.php
$fields = [
    AdSetFields::ACCOUNT_ID,
    AdSetFields::CAMPAIGN_ID,
    AdSetFields::ID,
    AdSetFields::NAME,
    AdSetFields::STATUS,
    AdSetFields::DAILY_BUDGET,
    AdSetFields::BID_AMOUNT,
    AdSetFields::BID_STRATEGY
];

// (OPTIONAL)
// You can pass in params to filter the response
// Filter by status: this will get all PAUSED campaigns
// https://developers.facebook.com/docs/marketing-api/reference/ad-account/adsets/
$params = ['effective_status'=>['PAUSED']];

$campaigns = $facebookAds->fetch()->getAdSets($fields, $params);

```

## Reports

Here are the **pre-built methods** for retrieving reports.

### Account Reports

Pull **Account** reports. This report uses [Insights API](https://developers.facebook.com/docs/marketing-api/insights).

```php
$accountReport  = $facebook->reports($dateFrom, $dateTo)
                            ->getAccountReport();
```

*Results: `getAccountReport()` (returns a [Laravel Collection](https://laravel.com/docs/collections) object, use `all()` for array)*

```
[0] => Array
(
    [account_id] => 0000000000
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

Pull **Campaign** reports. This report uses [Insights API](https://developers.facebook.com/docs/marketing-api/insights).

```php
$campaignReport  = $facebookAds->reports($dateFrom, $dateTo)
                             ->getCampaignReport();
```

*Results: `getCampaignReport()` (returns a [Laravel Collection](https://laravel.com/docs/collections) object, use `all()` for array)*

This is the response by default: you can alter the fields by `->setFields(Array)` and `setParams(Array)` [Fields and Parameters](https://developers.facebook.com/docs/marketing-api/insights/parameters/v11.0)

```
[0] => Array
(
    [account_id] => 0000000000
    [date] => 2018-12-15
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

Pull **Ad Set** reports. This report uses [Insights API](https://developers.facebook.com/docs/marketing-api/insights).

```php
$adsetReport  = $facebookAds->reports($dateFrom, $dateTo)
                            ->getAdSetReport();
```

*Results: `getAdSetReport()` (returns a [Laravel Collection](https://laravel.com/docs/collections) object, use `all()` for array)*

This is the response by default: you can alter the fields by `->setFields(Array)` and `setParams(Array)` [Fields and Parameters](https://developers.facebook.com/docs/marketing-api/insights/parameters/v11.0)

```
[0] => Array
(
    [account_id] => 0000000000
    [date] => 2021-12-18
    [adset_id] => 000000
    [adset_name] => Ad Set Name
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

### :white_check_mark: Choosing Fields for Insight Reports

You can also choose which fields you wish to return, `setFields(Array $fields)`. Here are the [Allowed Fields](https://github.com/facebook/facebook-php-business-sdk/blob/master/src/FacebookAds/Object/Fields/AdsInsightsFields.php)

```php
use FacebookAds\Object\Fields\AdsInsightsFields;

// This is the default field list
// https://github.com/facebook/facebook-php-business-sdk/blob/master/src/FacebookAds/Object/Fields/AdsInsightsFields.php
$fields = [
    AdsInsightsFields::ACCOUNT_ID,
    AdsInsightsFields::CAMPAIGN_ID,
    AdsInsightsFields::CAMPAIGN_NAME,
    AdsInsightsFields::IMPRESSIONS,
    AdsInsightsFields::CLICKS,
    AdsInsightsFields::CTR,
    AdsInsightsFields::CONVERSIONS,
    AdsInsightsFields::SPEND
];

$report = $facebookAds->reports($dateFrom, $dateTo)
    ->setFields($fields)->getCampaignReport()

```

## Jump To:
* [Home](README.md)
* [GoogleAds - Getting Started](GoogleAds-SDK.md)
* [BingAds - Getting Started](BingAds-SDK.md)
* [FacebookAds - Getting Started](FacebookAds-SDK.md)