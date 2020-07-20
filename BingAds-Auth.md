# BingAds API SDK  (Authentication Help)

This uses the [BingAds-PHP-SDK](https://github.com/BingAds/BingAds-PHP-SDK) for the [Bing Ads API](https://docs.microsoft.com/en-us/bingads/guides/get-started-php?view=bingads-12)

## (1) API Access

You need access before you can use the API

[Request Bing Ads API Access](https://advertise.bingads.microsoft.com/en-us/resources/bing-partner-program/request-bing-ads-api-access)

[Create Developer Token](https://developers.ads.microsoft.com/Account)

## (2) Register Your Application

Bing Requires you to register your app. This is where you get your client id, client secret and developer token.

[Register Your Application](https://apps.dev.microsoft.com)

Use `https://login.microsoftonline.com/common/oauth2/nativeclient` for the Redirect URLS input field.

*Note: Try using the platform "Native App" if you're having trouble.*

## (3) Generate Refresh Token

[Back to Readme](README.md)

## Sandbox

The [Bing Ads API Sandbox](https://docs.microsoft.com/en-us/advertising/guides/sandbox?view=bingads-13) can be used by changing the API environment using the `->setEnvironment()` function.
