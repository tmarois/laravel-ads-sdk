# GoogleAds API SDK (Authentication Guide)

This uses the [googleads-php-lib](https://github.com/googleads/googleads-php-lib) SDK for the [Google Ads API](https://developers.google.com/adwords/api/docs/guides/start).

## (1) API Access

You need access before you can use the Google Ads API. You can also setup a [test account](https://developers.google.com/google-ads/api/docs/first-call/overview#test_account) for sandbox mode.

[Request Google Ads API Access](https://services.google.com/fb/forms/newtoken/)

**Developer tokens** – are located in the API Center within your Google Ads account. Go to "Tools" -> "API Center"

## (2) Get Your API Client/Secret keys

Create an application using the [Google Cloud Platform](https://console.cloud.google.com)

1) Select your project/application

2) Enable Google Ads API

3) "Create Credentials" for OAuth client (fill in name, email and add in Google Ads API as the scope)

4) Create OAuth Client ID (select **Application type: Desktop App or Native App**)

5) Fill in .env `ADWORDS_OAUTH2_CLIENT_ID` and `ADWORDS_OAUTH2_CLIENT_SECRET`

## (3) Generate Refresh Token

Run `php artisan laravelads:token:generate --service=GoogleAds`

1) Copy and go to the URL shown after running that command

2) It will ask you to select/login to your Google Ads Account

3) Select "Allow" access to your application

4) Copy the code you see, and paste it into the command line; then copy and paste the refresh token you see into .env `ADWORDS_OAUTH2_REFRESH_TOKEN`

[Back to Readme](README.md)
