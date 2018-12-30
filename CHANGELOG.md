Change Log
==========

### 12/29/2018 - 1.0.6

#### Added
* Console `RefreshTokenCommand`, you can now generate a refresh token and follow authentication steps for BingAds
* Command: `php artisan laravelads:token:generate --service=BingAds`
* BingAds: Added new fetch methods `getCampaigns()` and `getAdGroups()`
* GoogleAds: Added `bid_type` on `getCampaigns()`
* Simplified the GoogleAds/BingAds Reports and made the responses consistent with both.

#### Changed
* Removed `campaign_name` from `getAdGroups()` on GoogleAds.

### 12/29/2018 - 1.0.5

#### Added
* Console `RefreshTokenCommand`, you can now generate a refresh token and follow authentication steps for GoogleAds
* Command: `php artisan laravelads:token:generate --service=GoogleAds`

### 12/29/2018 - 1.0.4

#### Changed
* Services: `googleAds()`, `bingAds()` and `facebookAds()` methods.

### 12/28/2018 - 1.0.3

#### Added
* GoogleAds: `AdGroupOperation` new method `getBidType()`.

#### Fixed
* GoogleAds: `AdGroupOperation` updated pull from the server when loaded.

### 12/28/2018 - 1.0.2

#### Added
* GoogleAds: `AdGroupOperation` with ability to change adgroup bids using `setBid()`
* GoogleAds: `AdGroupOperation` ability to change name and status of adgroups.
* GoogleAds: `AdGroupOperation` with a `save()` method that posts changes to Google Ads.
* GoogleAds: `AdGroupOperation` Added getter methods `getName()`, `getStatus()`, `getCampaignId()`, `getId()`

### 12/28/2018 - 1.0.1

#### Added
* Bing Reports (Account, Campaign and Ad Group Level)
* Bing Authentication Session and basic Bing service methods

### 12/27/2018 - 1.0.0
Initial Release for `Google Ads`
