# Sendwich for Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/vblinden/sendwich-laravel.svg?style=flat-square)](https://packagist.org/packages/vblinden/sendwich-laravel)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/vblinden/sendwich-laravel/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/vblinden/sendwich-laravel/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/vblinden/sendwich-laravel/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/vblinden/sendwich-laravel/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/vblinden/sendwich-laravel.svg?style=flat-square)](https://packagist.org/packages/vblinden/sendwich-laravel)

## Installation

You can install the package via composer:

```bash
composer require vblinden/sendwich-laravel
```

## Usage

Add the following to `config/mail.php`.

```php
'mailers' => [
    'sendwich' => [
        'transport' => 'sendwich',
    ],
],
```

Update the following values in your `.env`.

```text
MAIL_MAILER=sendwich

SENDWICH_API_KEY="sw_yourkey"
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [vblinden](https://github.com/vblinden)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
