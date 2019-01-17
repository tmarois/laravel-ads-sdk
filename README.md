# Laravel Ads SDK

[![Slack](http://timothymarois.com/a/slack-02.svg)](https://join.slack.com/t/basephp/shared_invite/enQtNDI0MzQyMDE0MDAwLWU3Nzg0Yjk4MjM0OWVmZDZjMjEyYWE2YjA1ODFhNjI2MzI3MjAyOTIyOTRkMmVlNWNhZWYzMTIwZDJlOWQ2ZTA)

For **Google Ads**, **Bing Ads** and **Facebook Ads** APIs.

This is a wrapper for connecting each ad source into your Laravel application. This SDK provides simpler and consistent methods across many ad source integrations than the traditional SDKs; ultimately making it a lot easier to implement in your projects. You shouldn't have to learn how to communicate and understand the responses to every API.

**The goal of this package is to provide you with one SDK to manage them all.**

### (1) Installation

Use [Composer](http://getcomposer.org/) to install package.

Run `composer require grayscale/laravel-ads-sdk:^1.2`

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

Having Trouble? [Learn More](BingAds-Auth.md)

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

> **NOTICE** – You will need to [Request Google Ads API Access](https://services.google.com/fb/forms/newtoken/).

### Jump to:

* **[Getting Started](GoogleAds-SDK.md)**
* [Fetching - All Campaigns](GoogleAds-SDK.md#fetch-all-campaigns)
* [Fetching - All Ad Groups](GoogleAds-SDK.md#fetch-all-ad-groups)
* [Reports - Account](GoogleAds-SDK.md#account-reports)
* [Reports - Campaign](GoogleAds-SDK.md#campaign-reports)
* [Reports - Ad Group](GoogleAds-SDK.md#ad-group-reports)
* [Management - Campaigns](GoogleAds-SDK.md#campaigns)
* [Management - Ad Groups](GoogleAds-SDK.md#ad-groups)
* [Advanced Options](GoogleAds-SDK.md#need-more-advanced-options)

# Bing Ads

This uses the [BingAds-PHP-SDK](https://github.com/BingAds/BingAds-PHP-SDK) for the [Bing Ads API](https://docs.microsoft.com/en-us/bingads/guides/get-started-php?view=bingads-12)

> **NOTICE** – You will need to [Request Bing Ads API Access](https://advertise.bingads.microsoft.com/en-us/resources/bing-partner-program/request-bing-ads-api-access).

### Jump to:

* **[Getting Started](BingAds-SDK.md)**
* [Fetching - All Campaigns](BingAds-SDK.md#fetch-all-campaigns)
* [Fetching - All Ad Groups](BingAds-SDK.md#fetch-all-ad-groups)
* [Reports - Account](BingAds-SDK.md#account-reports)
* [Reports - Campaign](BingAds-SDK.md#campaign-reports)
* [Reports - Ad Group](BingAds-SDK.md#ad-group-reports)
* [Management - Campaigns](BingAds-SDK.md#campaigns)
* [Management - Ad Groups](BingAds-SDK.md#ad-groups)


# Facebook Ads

This uses the [facebook-php-business-sdk](https://github.com/facebook/facebook-php-business-sdk) for [Facebook Marketing API](https://developers.facebook.com/docs/marketing-apis)

Coming Soon. Looking for contributors.

# Contributions

We are actively looking for new contributors.

If you want to help, join the slack channel and/or submit pull requests.

# License

**Laravel Ads SDK** (This Package) is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
