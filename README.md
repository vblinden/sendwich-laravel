# This is my package sendwich-laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/vblinden/sendwich-laravel.svg?style=flat-square)](https://packagist.org/packages/vblinden/sendwich-laravel)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/vblinden/sendwich-laravel/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/vblinden/sendwich-laravel/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/vblinden/sendwich-laravel/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/vblinden/sendwich-laravel/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/vblinden/sendwich-laravel.svg?style=flat-square)](https://packagist.org/packages/vblinden/sendwich-laravel)

This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

## Installation

You can install the package via composer:

```bash
composer require vblinden/sendwich-laravel
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="sendwich-laravel-config"
```

This is the contents of the published config file:

```php
return [
];
```

## Usage

```php
$sendwichLaravel = new Vblinden\SendwichLaravel();

echo $sendwichLaravel->echoPhrase('Hello, Vblinden!');
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
