# Laravel Ads SDK
For **Google Ads**, **Bing Ads** and **Facebook Ads** APIs.

This is a wrapper for connecting each ad source into your Laravel application using a single package. This SDK provides a simpler method API than the traditional SDKs, making it a lot easier to implement in your project.


## (1) Installation

Use [Composer](http://getcomposer.org/) to install package.

Run `composer require tmarois/laravel-ads-sdk:^1.0`

## (2) Laravel Config

**Google Ads**

```
php artisan vendor:publish --provider="tmarois\LaravelAds\Providers\GoogleAdsServiceProvider" --tag="config"
```

**Bing Ads**

```
php artisan vendor:publish --provider="tmarois\LaravelAds\Providers\BingAdsServiceProvider" --tag="config"
```

These will generate the config files located in your `/config` for `google-ads.php` and `bing-ads.php`.

# Google Ads

Uses [googleads-php-lib](https://github.com/googleads/googleads-php-lib) for [Google Ads API](https://developers.google.com/adwords/api/docs/guides/start)

### Configuration

Update `google-ads.php` with your API credentials.

### Getting Started

```php
// LaravelAds namespace
use LaravelAds;

// Get the GoogleAds API including the Client Accounts
$googleAds = LaravelAds::service('GoogleAds')->with(['CLIENT_ID']);

// Reports (Account, Campaign and Ad Group Level)
$accountReport  = $googleAds->reports([$dateFrom, $dateTo])->getAccountReport();
$campaignReport = $googleAds->reports([$dateFrom, $dateTo])->getCampaignReport();
$adgroupReprot  = $googleAds->reports([$dateFrom, $dateTo])->getAdGroupReport();

```

* Reporting
* Account Management

# Bing Ads

Uses [BingAds-PHP-SDK](https://github.com/BingAds/BingAds-PHP-SDK) for [Bing Ads API](https://docs.microsoft.com/en-us/bingads/guides/get-started-php?view=bingads-12)

### Configuration

Update `bing-ads.php` with your API credentials.

### Getting Started

* Reporting
* Account Management

# Facebook Ads

Coming Soon. Looking for contributors.
