# Laravel Ads SDK

[Join the Discord](https://discord.gg/g3W49zdWm2) – For support, updates and collaboration.

For **Google Ads**, **Bing Ads** and **Facebook Ads** API.

This is a wrapper for connecting each ad source into your Laravel application. This SDK provides simpler and consistent methods across many ad source integrations than the traditional SDKs; ultimately making it a lot easier to implement in your projects. You shouldn't have to learn how to communicate and understand the responses to every API.

### (1) Installation

Use [Composer](http://getcomposer.org/) to install package.

Run `composer require tmarois/laravel-ads-sdk`

### (2) Laravel Config

Run `php artisan vendor:publish`, If you see multiple options, select the one that says `laravel-ads-sdk`

**Next**, copy this to your `.env` and update with your credentials (if you dont have credentials, continue to the next step).

```
ADWORDS_DEVELOPER_TOKEN=""
ADWORDS_OAUTH2_CLIENT_ID=""
ADWORDS_OAUTH2_CLIENT_SECRET=""
ADWORDS_OAUTH2_REFRESH_TOKEN=""

BING_DEVELOPER_TOKEN=""
BING_CLIENT_ID=""
BING_CLIENT_SECRET=""
BING_REFRESH_TOKEN=""

FB_APP_ID=""
FB_APP_SECRET=""
FB_ACCESS_TOKEN=""
FB_REFRESH_TOKEN=""
```

### (3.a) For GoogleAds

You will need your developer token, client id and client secret to continue. [Learn More](GoogleAds-Auth.md)

*Follow the steps in the command line to **generate a refresh token**.*

Run `php artisan laravelads:token:generate --service=GoogleAds`

Having Trouble? [Learn More](GoogleAds-Auth.md)

### (3.b) For BingAds

You will need your developer token, client id and client secret to continue. [Learn More](BingAds-Auth.md)

*Follow the steps in the command line to **generate a refresh token**.*

Run `php artisan laravelads:token:generate --service=BingAds`

Having Trouble? [Learn More](BingAds-Auth.md)

### (3.c) For FacebookAds

Coming soon

### (4) Usage

Accessing `GoogleAds`, `BingAds` or `FacebookAds` use the following:

```php
// The namespace to the Facade for laravel Ads SDK
use LaravelAds;

// calling Google Ads and including the Account ID
$googleAds = LaravelAds::googleAds()->with('ACCOUNT_ID');

// calling Bing Ads and including the Account ID
$bingAds = LaravelAds::bingAds()->with('ACCOUNT_ID');

// calling Facebook Ads and including the Account ID
$facebookAds = LaravelAds::facebookAds()->with('ACCOUNT_ID');
```

# Google Ads

This uses the [googleads-php-lib](https://github.com/googleads/googleads-php-lib) SDK for the [Google Ads API](https://developers.google.com/adwords/api/docs/guides/start)

> **NOTICE** – You will need to [Request Google Ads API Access](https://services.google.com/fb/forms/newtoken/).

[Need help with authentication?](GoogleAds-Auth.md)

#### Management
* [Fetching - All Campaigns](GoogleAds-SDK.md#fetch-all-campaigns)
* [Fetching - All Ad Groups](GoogleAds-SDK.md#fetch-all-ad-groups)
* [Management - Campaigns](GoogleAds-SDK.md#campaigns)
* [Management - Ad Groups](GoogleAds-SDK.md#ad-groups)
* [Offline Conversion Import](GoogleAds-SDK.md#offline-conversion-import)
* [Manual Configuration](GoogleAds-SDK.md#manual-configuration)
* [Advanced Options](GoogleAds-SDK.md#need-more-advanced-options)

#### Reports
* [Account Performance](GoogleAds-SDK.md#account-reports)
* [Campaign Performance](GoogleAds-SDK.md#campaign-reports)
* [Ad Group Performance](GoogleAds-SDK.md#ad-group-reports)
* [Final URL Performance](GoogleAds-SDK.md#final-url-performance-report)
* [Placement Domain Performance](GoogleAds-SDK.md#placement-domain-performance-report)
* [Placement URL Performance](GoogleAds-SDK.md#placement-url-performance-report)
* [Search Term Performance](GoogleAds-SDK.md#search-term-performance-report)
* [Age Range Performance](GoogleAds-SDK.md#age-range-performance-report)
* [Gender Performance](GoogleAds-SDK.md#gender-performance-report)

# Bing Ads

This uses the [BingAds-PHP-SDK](https://github.com/BingAds/BingAds-PHP-SDK) for the [Bing Ads API](https://docs.microsoft.com/en-us/bingads/guides/get-started-php?view=bingads-12)

> **NOTICE** – You will need to [Request Bing Ads API Access](https://advertise.bingads.microsoft.com/en-us/resources/bing-partner-program/request-bing-ads-api-access).

[Need help with authentication or sandbox mode?](BingAds-Auth.md)

#### Management
* [Fetching - Get Customers](BingAds-SDK.md#fetch-customers)
* [Fetching - All Campaigns](BingAds-SDK.md#fetch-all-campaigns)
* [Fetching - All Ad Groups](BingAds-SDK.md#fetch-all-ad-groups)
* [Management - Campaigns](BingAds-SDK.md#campaigns)
* [Management - Ad Groups](BingAds-SDK.md#ad-groups)
* [Offline Conversion Import](BingAds-SDK.md#offline-conversion-import)
* [Manual Configuration](BingAds-SDK.md#manual-configuration)

#### Reports
* [Account Performance](BingAds-SDK.md#account-reports)
* [Campaign Performance](BingAds-SDK.md#campaign-reports)
* [Ad Group Performance](BingAds-SDK.md#ad-group-reports)
* [Final URL Performance](BingAds-SDK.md#final-url-performance-report)
* [Search Term Performance](BingAds-SDK.md#search-term-performance-report)
* [Age Range Performance](BingAds-SDK.md#age-range-performance-report)
* [Gender Performance](BingAds-SDK.md#gender-performance-report)
* [Custom Fields](BingAds-SDK.md#custom-fields)


# Facebook Ads

This uses the [facebook-php-business-sdk](https://github.com/facebook/facebook-php-business-sdk) for [Facebook Marketing API](https://developers.facebook.com/docs/marketing-apis)

Coming soon.

# Contributions

We are actively looking for new contributors.

If you want to contribute, join the slack channel and/or submit pull requests.

# License

**Laravel Ads SDK** (This Package) is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT). USE AT YOUR OWN RISK. Laravel Ads SDK is a tool to help you manage your accounts, it does not guarantee features listed here will work as described. If you do find a bug, please feel free to submit an issue. *This package is not affiliated with Laravel LLC or the Laravel Framework team.*
