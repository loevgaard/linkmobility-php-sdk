# linkmobility-php-sdk

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

PHP SDK for the [Linkmobility REST API v2](https://linkmobility.atlassian.net/wiki/spaces/COOL/pages/26017821/LINK+Mobility+DK+Rest+API+v2)

## Install

Via Composer

``` bash
$ composer require loevgaard/linkmobility-php-sdk
```

## Usage

```php
<?php
require_once 'vendor/autoload.php';

$message = new \Loevgaard\Linkmobility\Payload\Message();
$message
    ->setMessage('Message')
    ->setSender('Sender')
    ->addRecipient('+4511223344');

$client = new \Loevgaard\Linkmobility\Client('insert api key');
$client->postMessage($message);
```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CONDUCT](CONDUCT.md) for details.

## Security

If you discover any security related issues, please email joachim@loevgaard.dk instead of using the issue tracker.

## Credits

- [Joachim Løvgaard][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/loevgaard/linkmobility-php-sdk.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/loevgaard/linkmobility-php-sdk/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/loevgaard/linkmobility-php-sdk.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/loevgaard/linkmobility-php-sdk.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/loevgaard/linkmobility-php-sdk.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/loevgaard/linkmobility-php-sdk
[link-travis]: https://travis-ci.org/loevgaard/linkmobility-php-sdk
[link-scrutinizer]: https://scrutinizer-ci.com/g/loevgaard/linkmobility-php-sdk/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/loevgaard/linkmobility-php-sdk
[link-downloads]: https://packagist.org/packages/loevgaard/linkmobility-php-sdk
[link-author]: https://github.com/loevgaard
[link-contributors]: ../../contributors
