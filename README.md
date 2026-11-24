# Yireo_AdminReactComponents

<!-- badges.specs.start -->
<div class="github-specs-badges">

![Magento version](https://img.shields.io/badge/Magento-2.4.6%20%7C%202.4.9-orange)
![PHP version](https://img.shields.io/badge/PHP-8.2%E2%80%938.5-777BB4)
![License](https://img.shields.io/badge/License-OSL--3.0-blue)
![Latest Version](https://img.shields.io/packagist/v/yireo/magento2-admin-react-components)

</div>
<!-- badges.specs.end -->

> This module is deprecated in favor of [Loki Admin Components](https://loki-extensions.com/admin-components)


### Installation
Install this package via composer:
```bash
composer require yireo/magento2-admin-react-components
```

Next, enable this module:
```bash
bin/magento module:enable Yireo_AdminReactComponents
```

## Security notes
- The AJAX endpoints use the core ACL resources `Magento_Customer::manage`, `Magento_Sales::actions_view` and `Magento_Catalog::products`.
- Adobe Commerce admin role scopes (AdminGws, restricting a role to specific websites or stores) are not enforced: an admin with the ACL resource can search and look up entities of all websites.
- Modules using these components must cast the selected ID to an integer when saving it.

## Current status

<!-- badges.test.start -->
<div class="github-pipeline-badges">

![Static Tests](https://img.shields.io/github/actions/workflow/status/yireo/Yireo_AdminReactComponents/static-tests.yml?label=static-tests)
![Unit Tests](https://img.shields.io/github/actions/workflow/status/yireo/Yireo_AdminReactComponents/unit-tests.yml?label=unit-tests)
![Integration Tests](https://img.shields.io/github/actions/workflow/status/yireo/Yireo_AdminReactComponents/integration-tests.yml?label=integration-tests)
![Playwright](https://img.shields.io/github/actions/workflow/status/yireo/Yireo_AdminReactComponents/playwright.yml?label=playwright)
![DI Compilation](https://img.shields.io/github/actions/workflow/status/yireo/Yireo_AdminReactComponents/compile.yml?label=compile)

</div>
<!-- badges.test.end -->
