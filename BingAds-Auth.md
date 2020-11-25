# BingAds API SDK  (Authentication Help)

This uses the [BingAds-PHP-SDK](https://github.com/BingAds/BingAds-PHP-SDK) for the [Bing Ads API](https://docs.microsoft.com/en-us/bingads/guides/get-started-php?view=bingads-12)

## (1) API Access

You need access before you can use the API, follow the links below to help you gain access.

~~Request Bing Ads API Access~~ (link is no longer available)

[Create Developer Token](https://developers.ads.microsoft.com/Account)

[Help Guide – Quick Start](https://docs.microsoft.com/en-us/advertising/guides/get-started?view=bingads-13#quick-start-production)

[Help Guide - Get a Developer Token](https://docs.microsoft.com/en-us/advertising/guides/get-started?view=bingads-13#get-developer-token)

## (2) Register Your Application

Bing Requires you to register your app. This is where you get your client id, client secret and developer token.

[Register Your Application (Azure)](https://go.microsoft.com/fwlink/?linkid=2083908)

Use `https://login.microsoftonline.com/common/oauth2/nativeclient` for the Redirect URLS input field.

*Note: Try using the platform "Native App" if you're having trouble.*

## (3) Generate Refresh Token

Run `php artisan laravelads:token:generate --service=BingAds`

[Back to Readme](README.md)

## Sandbox

The [Bing Ads API Sandbox](https://docs.microsoft.com/en-us/advertising/guides/sandbox?view=bingads-13) can be used by changing the API environment using the `->setEnvironment('Sandbox')` method.