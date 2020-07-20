# GoogleAds API SDK (Authentication Help)

This uses the [googleads-php-lib](https://github.com/googleads/googleads-php-lib) SDK for the [Google Ads API](https://developers.google.com/adwords/api/docs/guides/start).

## (1) API Access

You need access before you can use the API

> **NOTICE** – You will need to [Request Google Ads API Access](https://services.google.com/fb/forms/newtoken/).

Developer tokens are located in the "API Center" within your Google Ads account.

## (2) Get API Client/Secret keys

Create an application using the [Google Cloud Platform](https://console.cloud.google.com)

Set application type to "other"

## (3) Generate Refresh Token

Run `php artisan laravelads:token:generate --service=GoogleAds`

After running the generate refresh command, if you see an error `Error: redirect_uri_mismatch` that means you have set your client to be "web application", you need to make it "Other" type, this allows the `redirect_uri` to use `urn:ietf:wg:oauth:2.0:oob` instead.

[Back to Readme](README.md)
