Change Log
==========

## [1.4.0] - 2021-10-26

### Added
* Added Macroable trait to Service classes for extensibility [Readme](README.md#Customization)

### Changed
* Updated facebook php sdk to use v12 instead of v11

-------------------------------------------------------

## [1.3.0] - 2021-07-25

### Added
* New facebook integration [Readme](FacebookAds-SDK.md#facebookads---documentation)
* New facebook API v11 using [Marketing API](https://developers.facebook.com/docs/marketing-apis) and [facebook-php-business-sdk](https://github.com/facebook/facebook-php-business-sdk)
* Facebook: Fetch Campaigns and AdSets
* Facebook: Insight reports for Account, Campaign and AdSet performance

-------------------------------------------------------

## [1.2.23] - 2021-06-08

### Fixed
* Fixed bing offline conversion response by changing `click` to `click_id` and adding `name` and `time` to match Google

-------------------------------------------------------

## [1.2.22] - 2021-06-03

### Added
* Added ability to specify fields for Bing Ads reports
* Added getCustomers() endpoint for Bing Ads

### Changed
* Made the redirect_uri config setting an env variable
* Updated the laravel vendor:publish to show laravel-ads-sdk instead of config

### Fixed
* Url encoded spaces in OAuth Grant URL

-------------------------------------------------------

## [1.2.21] - 03-31-2021

### Added
* For the google/bing offline conversions import and with setting of output value, now returns name and time.

### Fixed
* Fixed offline conversions for both google/bing to return the proper click id on errors (was returning the same click id for all errors)

-------------------------------------------------------

## [1.2.20] - 03-30-2021

### Added
* Added a way to output the conversion value of success/error offline conversion imports for google/bing ads

-------------------------------------------------------

## [1.2.19] - 12-27-2020

### Changed
* Updated composer.json Google Ads API to v49 based on #19 (no other AdWords changes were made)

-------------------------------------------------------

## [1.2.18] - 06-02-2020

### Changed
* Updated Google Ads API to v47 (from v40) to fix the dependency issue #10

-------------------------------------------------------

## [1.2.17] - 04-12-2020

-------------------------------------------------------

### Changed
* Google Ads `getTargetCpa()` suppressing any null errors when trying to get the target cpa value.

-------------------------------------------------------

## [1.2.16] - 03-30-2020

### Added
* Ability to set the configuration manaually (overriding the laravel config settings) by using `configuration(array)`

-------------------------------------------------------

## [1.2.15] - 12-13-2019

### Changed
* Cleaning up more `BingAds` for Soap errors due to undefined variables. (raw output of errors).

-------------------------------------------------------

## [1.2.14] - 12-12-2019

### Changed
* Cleaned up `BingAds` for Soap errors due to request of `__getLastRequest()` undefined errors.

-------------------------------------------------------

## [1.2.13] - 11-07-2019

### Added
* Added `InvalidCredentials` Error code with Offline Conversion Import on `BingAds`
* Added `ApiVersionNoLongerSupported` Error code with Offline Conversion Import on `BingAds`
* Added `withRedirectUri()` by default for `BingAds` Auth
* Added config variable `redirect_uri` by default it will use `https://login.microsoftonline.com/common/oauth2/nativeclient`

### Changed
* Fixed `BingAds` now using `^0.12` instead of deprecated `v0.11` as of 11/6 (version of SDK)
* Changed `BingAds` Services to use `V13` namespace instead of `V12` (version of API)

-------------------------------------------------------

## [1.2.12] - 03-29-2019

### Added
* Added Offline Conversion Import services for both `BingAds` and `GoogleAds`.
* Added `withCustomerId()` to the `BingAds` service as some requests require it.

-------------------------------------------------------

## [1.2.11] - 03-29-2019

### Changed
* Fixed BingAds from running too many rows for Search Terms (using Summary instead of Daily feed)
* Updated BingAds to increase time checking for API reports
* Updated BingAds Report Names and Aggregation to choose, daily, hourly, summary on account level.
* Updated GoogleAds API to version 40

-------------------------------------------------------

## [1.2.10] - 03-04-2019

### Changed
* Fixed GoogleAds Reports by bad parsing of csv data. Due to comma issues. Now using `str_getcsv`
* GoogleAds Report: Added default columns to rows (based on headers) if the column was missing

-------------------------------------------------------

## [1.2.9] - 02-26-2019

### Added
* Added `campaign_name` to the resposne of `getAdGroupReport()`.
* Added Reports: `getFinalUrlReport()` on both Google/Bing (it returns per campaign)
* Added Reports: `getPlacementReport()` on Google only
* Added Reports: `getPlacementUrlReport()` on Google only
* Added Reports: `getSearchTermReport()` on Google/Bing
* Added Reports: `getAgeRangeReport()` on Google/Bing
* Added Reports: `getGenderReport()` on Google/Bing

-------------------------------------------------------

## [1.2.8] - 02-22-2019

### Changed
* Fixed fetching `getAdGroups()` on Bing causing undefined error when AdGroups not returned.

-------------------------------------------------------

## [1.2.7] - 02-02-2019

### Changed
* Added `getCampaigns()` and `getAdGroups()` to now work with paging. Google allows only 10,000 results per page. Pre-defined 5,000 per page in this SDK for safety. It will be handled automatically and give you the entire total in the response.
* Added `$filters` array within the `getCampaigns()` and `getAdGroups()`, using the `Predicate` and `setPredicates` Google Ads functionality. (Note: this currently will only use the `PredicateOperator::IN` operator.)

-------------------------------------------------------

## [1.2.6] - 02-01-2019

### Changed
* Updated GoogleAds API from `v201802` to  `v201809`

-------------------------------------------------------

## [1.2.5] - 01-25-2019

### Changed
* GoogleAds Reports now allow for advanced customization, making `reportDownload()` a public method.
* GoogleAds Reports now allow `aggregate()` to be used with multiple calls.

-------------------------------------------------------

## [1.2.4] - 01-22-2019

### Added
* GoogleAds: `AveragePosition` which equals `avg_position` within AdGroup Report `getAdGroupReport`.
* BingAds: `AveragePosition` which equals `avg_position` within AdGroup Report `getAdGroupReport()`.

-------------------------------------------------------

## [1.2.3] - 01-18-2019

### Added
* `setTargetCpa()` on Bing/Google campaigns.

-------------------------------------------------------

## [1.2.2] - 01-17-2019

### Changed
* Fixed bing report response once again!

-------------------------------------------------------

## [1.2.1] - 01-17-2019

### Changed
* Fixed Bing Report Download when download fails due to "no data in report" (now returns a empty response)

-------------------------------------------------------

## [1.2.0] - 01-17-2019

* Overhaul of Google and Bing APIs
* Includes Google/Bing Campaign and AdGroup Management Features.
* Updated Readme with all Documentation for both Google/Bing
* Improved consistency between both Google/Bing

-------------------------------------------------------

## [1.1.6] - 01-14-2019

### Fixed
* Fixed new bid type method returns the correct types now.

-------------------------------------------------------

## [1.1.5] - 01-14-2019

### Fixed
* Added `Campaign Id` into Bing Ad Group response.

-------------------------------------------------------

## [1.1.4] - 01-14-2019

### Changed
* Changed Bing Ad Group status from `Active` to `ENABLED` (matching GoogleAds)
* Fetching AdGroups now use the AdGroup Response object.

-------------------------------------------------------

## [1.1.3] - 01-14-2019

### Added
* Google: Added `type` for `AdGroupType` in `getAdGroups()`

### Changed
* Changed the Bid Type to always return a uniform set (`CPC`,`ECPC`,`CPA`,`CPM`)

-------------------------------------------------------

## [1.1.2] - 01-14-2019

### Added
* Google: Ability to check if an ad group is Enhanced CPC using `isEnhancedCpc()`
* Google: Added `ENHANCED_CPC` for bid strategy response in fetch ad groups

-------------------------------------------------------

## [1.1.1] - 01-05-2019

### Added
* For Google/Bing get the active bid with `getBid()` on adgroups.

-------------------------------------------------------

## [1.1.0] - 01-05-2019

### Changed
* Response of `adGroup` for both Google/Bing
* Request to the servers will use `AdGroupRequest`
* Response from the servers will use `AdGroupResponse`
* Bing now has edit ability (and uses the same methods as Google)
* Added new Set/Get Methods to Google/Bing

-------------------------------------------------------

## [1.0.7] - 12-30-2018

### Added
* Added `toCollection()` on `ReportDownload`, instead of sending back raw array, we will send back as a Collection. You can use `all()` if you want the basic array response.

### Changed
* The default `ReportDownload` response to `toCollection()` instead of `toArray()`

-------------------------------------------------------

## [1.0.6] - 12-29-2018

### Added
* Console `RefreshTokenCommand`, you can now generate a refresh token and follow authentication steps for BingAds
* Command: `php artisan laravelads:token:generate --service=BingAds`
* BingAds: Added new fetch methods `getCampaigns()` and `getAdGroups()`
* GoogleAds: Added `bid_type` on `getCampaigns()`

### Changed
* Simplified the GoogleAds/BingAds Reports and made the responses consistent with both.

-------------------------------------------------------

## [1.0.5] - 12-29-2018

### Added
* Console `RefreshTokenCommand`, you can now generate a refresh token and follow authentication steps for GoogleAds
* Command: `php artisan laravelads:token:generate --service=GoogleAds`

-------------------------------------------------------

## [1.0.4] - 12-29-2018

### Changed
* Services: `googleAds()`, `bingAds()` and `facebookAds()` methods.

-------------------------------------------------------

## [1.0.3] - 12-28-2018

### Added
* GoogleAds: `AdGroupOperation` new method `getBidType()`.

### Fixed
* GoogleAds: `AdGroupOperation` updated pull from the server when loaded.

-------------------------------------------------------

## [1.0.2] - 12-28-2018

### Added
* GoogleAds: `AdGroupOperation` with ability to change adgroup bids using `setBid()`
* GoogleAds: `AdGroupOperation` ability to change name and status of adgroups.
* GoogleAds: `AdGroupOperation` with a `save()` method that posts changes to Google Ads.
* GoogleAds: `AdGroupOperation` Added getter methods `getName()`, `getStatus()`, `getCampaignId()`, `getId()`

-------------------------------------------------------

## [1.0.1] - 12-28-2018

### Added
* Bing Reports (Account, Campaign and Ad Group Level)
* Bing Authentication Session and basic Bing service methods

-------------------------------------------------------

## [1.0.0] - 12-27-2018
* Initial Release for `Google Ads`
