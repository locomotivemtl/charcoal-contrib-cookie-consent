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

## Service Provider

### Parameters

--TBD--

### Services

--TBD--

## Configuration

--TBD--

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
