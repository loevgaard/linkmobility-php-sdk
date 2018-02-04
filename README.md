# Link Mobility PHP SDK

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]

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

use Loevgaard\Linkmobility\Client;
use Loevgaard\Linkmobility\Request\PostMessageRequest;
use Loevgaard\Linkmobility\Response\BatchStatusResponse;
use Loevgaard\Linkmobility\ValueObject\Sender;
use Loevgaard\Linkmobility\ValueObject\Message;
use Loevgaard\Linkmobility\ValueObject\Recipient;

$request = new PostMessageRequest(new Sender('Sender'), new Message('Message'), [new Recipient('+4511223344')]);

$client = new Client('insert api key');

/** @var BatchStatusResponse $response */
$response = $client->request($request);

print_r($response);
```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Testing

``` bash
$ composer test
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/loevgaard/linkmobility-php-sdk.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/loevgaard/linkmobility-php-sdk/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/loevgaard/linkmobility-php-sdk.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/loevgaard/linkmobility-php-sdk.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/loevgaard/linkmobility-php-sdk
[link-travis]: https://travis-ci.org/loevgaard/linkmobility-php-sdk
[link-scrutinizer]: https://scrutinizer-ci.com/g/loevgaard/linkmobility-php-sdk/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/loevgaard/linkmobility-php-sdk
[link-author]: https://github.com/loevgaard
