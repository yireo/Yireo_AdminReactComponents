# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [1.0.6] - 23 November 2026
### Security
- Require admin authentication and ACL on the CustomerLabel AJAX controller, which disclosed customer name, email, group and website to unauthenticated visitors (CWE-306)
- Use core ACL resources per entity (Magento_Customer::manage, Magento_Sales::actions_view, Magento_Catalog::products) for all AJAX controllers
- Allowlist sort fields, limit search input and escape LIKE wildcards in search controllers
- Output AJAX URLs as JSON via the secure HTML renderer

### Fixed
- Fix sorting by customer group and website
- Handle missing customer groups and websites in CustomerData
- Encode query parameters in AJAX requests
- Declare missing Magento module dependencies in composer.json and module.xml

### Removed
- Remove PHP 8.1 support, add PHP 8.5 support

## [1.0.5] - 19 April 2023
### Fixed
- Rename composer package

## [1.0.4] - 25 March 2023
### Fixed
- Apply dataprovider to other searches as well
- Make all props configurable
- Make sure original KO viewModel is also updated via React

## [1.0.3] - 25 March 2023
### Fixed
- Re-add data provider
- Add some debugging

## [1.0.2] - 25 March 2023
### Fixed
- Reuse same model-data in both customer calls
- Move from controller extends to implement
- Add composer dependency with framework properly

## [1.0.1] - 25 March 2023
### Fixed
- Allow for usage in other modules via `dataProvider` prop

## [1.0.0] - 24 March 2023
### Added
- Initial copying from EmailTester extension
