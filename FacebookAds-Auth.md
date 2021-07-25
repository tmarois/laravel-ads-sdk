# Facebook Ads SDK (Authentication Guide)

This uses the [facebook-php-business-sdk](https://github.com/facebook/facebook-php-business-sdk) for [Facebook Marketing API](https://developers.facebook.com/docs/marketing-apis).

## Guide to gain API Access

1) Create an app at [developers.facebook.com](https://developers.facebook.com/)

2) Copy the App Id and paste it into env `FB_APP_ID`

3) On the menu, go to settings and get "App Secret" copy and paste this into the env `FB_APP_SECRET`

4) Add the product Marketing API to your app, selecting permissions you would like to grant the app and click "Get Token".

5) Copy and paste the token shown into the env `FB_ACCESS_TOKEN`

[Back to Readme](README.md#for-facebookads)


### External Resources:

- [Quick Start Guide](https://github.com/facebook/facebook-php-business-sdk?fbclid=IwAR30iukDiegrACx9BJnbhJKIU2X3SaayDAr_YkbQzXU6C_96PFd-27mj5kc#quick-start)
- [Access Tokens](https://developers.facebook.com/docs/facebook-login/access-tokens)