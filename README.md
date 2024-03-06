Charcoal Cookie Consent
=======================

[![License][badge-license]](./LICENSE)
[![Latest stable version][badge-version]](https://packagist.org/packages/locomotivemtl/charcoal-contrib-cookie-consent)
[![Supported PHP versions][badge-php]](./composer.json)

The [Charcoal][charcoal/charcoal] Cookie Consent package provides
an integration with [vanilla-cookieconsent] for a simple cross-browser
plugin written in vanilla JS that is configurable from the Admin.

## Installation

```shell
composer require locomotivemtl/charcoal-contrib-cookie-consent
```

### Setup

#### Charcoal Module

For Charcoal projects, the module can be registered from your configuration file:

```json
{
    "modules": {
        "charcoal/cookie-consent/cookie-consent": {}
    }
}
```

The module will automatically register the service provider, the metadata path,
and the [Admin][charcoal/admin] dashboard routing.

#### Charcoal Service Provider

If you are not using the module, the service provider can be registered
from your configuration file:

```json
{
    "service_providers": {
        "charcoal/cookie-consent/cookie-consent": {}
    }
}
```

Consult the [package configuration files](./config) for what to add to your project.

## Models

After the module or service provider has been registered, create the database table
for the primary model for configuring and disclosing cookie consent information:

```shell
./vendor/bin/charcoal admin/object/table/create --obj-type=charcoal/cookie-consent/model/disclosure
./vendor/bin/charcoal admin/object/table/create --obj-type=charcoal/cookie-consent/model/category
```

## Service Provider

### Parameters

--TBD--

### Services

--TBD--

## Configuration

If you are using the module, the package can be configured via its module registration:

```jsonc
{
    "modules": {
        "charcoal/cookie-consent/cookie-consent": {
            // See available options below.
        }
    }
}
```

Alternatively, it can be configured via your application configuration:

```jsonc
{
    "cookie_consent": {
        // See available options below.
    }
}
```

## Usage

--TBD--

## Credits

* [Locomotive](https://locomotive.ca/)

## License

Charcoal is licensed under the [MIT license](./LICENSE).

## Resources

* [Contributing](./CONTRIBUTING.md)
* [Report issues](https://github.com/locomotivemtl/charcoal-contrib-cookie-consent/issues) and
  [send pull requests](https://github.com/locomotivemtl/charcoal-contrib-cookie-consent/pulls)
  in the [main Charcoal repository](https://github.com/charcoalphp/charcoal)

---

🚂

[charcoal/admin]:        https://github.com/charcoalphp/admin
[charcoal/charcoal]:     https://github.com/charcoalphp/charcoal
[vanilla-cookieconsent]: https://github.com/orestbida/cookieconsent

[badge-license]:         https://img.shields.io/packagist/l/locomotivemtl/charcoal-contrib-cookie-consent.svg?style=flat-square
[badge-php]:             https://img.shields.io/packagist/php-v/locomotivemtl/charcoal-contrib-cookie-consent?style=flat-square&logo=php
[badge-version]:         https://img.shields.io/packagist/v/locomotivemtl/charcoal-contrib-cookie-consent.svg?style=flat-square&logo=packagist
