# BingAds API SDK  (Authentication Guide)

*Note: These steps were tested as of June 3rd, 2021, you may need modify these steps to work for your specific project.*

This uses the [BingAds-PHP-SDK](https://github.com/BingAds/BingAds-PHP-SDK) for the [Bing Ads API](https://docs.microsoft.com/en-us/bingads/guides/get-started-php?view=bingads-12)

## (1) API Access

You need access before you can use the API, follow the links below to help you gain access.

[Create Developer Token](https://developers.ads.microsoft.com/Account)

[Help Guide – Quick Start](https://docs.microsoft.com/en-us/advertising/guides/get-started?view=bingads-13#quick-start-production)

[Help Guide - Get a Developer Token](https://docs.microsoft.com/en-us/advertising/guides/get-started?view=bingads-13#get-developer-token)

## (2) Register Your Application

Bing Requires you to register your app. This is where you get your client id and client secret.

1) [Register Your Application (Azure)](https://go.microsoft.com/fwlink/?linkid=2083908)

2) **Supported account types**: select "Accounts in any organizational directory (Any Azure AD directory - Multitenant)"

3) Redirect URI: Web type, then add `https://login.microsoftonline.com/common/oauth2/nativeclient`

4) Fill in .env `BING_CLIENT_ID` with the "Application (client) ID" from the overview page for your app

5) Go to Certificates & secrets and create a new secret

6) Copy the "value" of this new secret into the .env `BING_CLIENT_SECRET` (not the secret ID)

## (3) Generate Refresh Token

Run `php artisan laravelads:token:generate --service=BingAds`

1) Copy and go to the URL shown after running that command

2) It will ask you to login and "approve access" to your application

3) You will be redirected to a URL, most likely a white page. COPY the url from in the address bar and paste it into the command line.

4) Copy and paste the refresh token you see into .env `BING_REFRESH_TOKEN`

[Back to Readme](README.md)

## Sandbox

The [Bing Ads API Sandbox](https://docs.microsoft.com/en-us/advertising/guides/sandbox?view=bingads-13) can be used by changing the API environment using the `->setEnvironment('Sandbox')` method.