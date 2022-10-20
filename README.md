# Guzzle Formatter

[![Tests status](https://github.com/joephoffland/guzzle-formatter/actions/workflows/testing.yml/badge.svg)](https://github.com/jhoffland/guzzle-formatter/actions/workflows/testing.yml)
[![StyleCI status](https://github.styleci.io/repos/470917304/shield?style=flat&branch=main)](https://github.styleci.io/repos/470917304?branch=main)

PHP library for formatting Guzzle requests and responses to [HTTP messages](https://developer.mozilla.org/en-US/docs/Web/HTTP/Messages).


## Installation

[![Latest stable version](http://poser.pugx.org/jhoffland/guzzle-formatter/v)](https://packagist.org/packages/jhoffland/guzzle-formatter)
[![Total downloads](http://poser.pugx.org/jhoffland/guzzle-formatter/downloads)](https://packagist.org/packages/jhoffland/guzzle-formatter)

Install this library directly with Composer:

```shell
composer require jhoffland/guzzle-formatter
```

Add `--dev` if this library is not needed in a production environment.

## Usage

```php
use GuzzleFormatter\RequestFormatter;
use GuzzleFormatter\ResponseFormatter;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

$request = new Request('GET', 'https://github.com/jhoffland/guzzle-formatter');
echo (new RequestFormatter())->http($request); // Results in the formatted HTTP request message.

$response = (new Client())->send($request);
echo (new ResponseFormatter())->http($response); // Results in the formatted HTTP response message.
```

### Using middleware

Logging all requests performed and/or responses received by a Guzzle client, using [middleware](https://docs.guzzlephp.org/en/stable/handlers-and-middleware.html#middleware).

The HTTP request and/or response messages are written to the file, specified when creating the `HttpFormatterMiddleware` class instance.

```php
use GuzzleFormatter\Middleware\HttpFormatterMiddleware;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;

$formatterMiddleware = new HttpFormatterMiddleware('/path/to/output-file.txt');

$handlerStack = HandlerStack::create();

$handlerStack->after('prepare_body', $formatterMiddleware->requests(), 'http_request_formatter');
$handlerStack->after('prepare_body', $formatterMiddleware->responses(), 'http_response_formatter');

$client = new Client([
    'handler' => $handlerStack,
]);
$client->get('https://github.com/jhoffland/guzzle-formatter');
```

### Available options

#### 1. End of Line character

Available when creating an instance of [`RequestFormatter`](src/RequestFormatter.php) & [`ResponseFormatter`](src/ResponseFormatter.php).

#### 2. Hide sensitive headers

Available for when formatting an HTTP message with [`RequestFormatter`](src/RequestFormatter.php) & [`ResponseFormatter`](src/ResponseFormatter.php) & when creating an instance of [`HttpFormatterMiddleware`](src/Middleware/HttpFormatterMiddleware.php).<br />
The headers seen as sensitive can be found in the array [`Formatter::SENSITIVE_HEADERS`](src/Formatter.php).


## Supported PHP & package versions

[![PHP version](http://poser.pugx.org/jhoffland/guzzle-formatter/require/php)](composer.json)
[![Guzzle version](https://poser.pugx.org/jhoffland/guzzle-formatter/require/guzzlehttp/guzzle)](composer.json)

This library is tested with PHP 7.4, 8.0 and 8.1.<br />
Check the [`composer.json`](composer.json) file for the supported package versions.

Feel free to add support for additional versions to this library.


## Contributing

Feel free to contribute to this library. Contribute by forking the [GitHub repository](https://github.com/jhoffland/guzzle-formatter) and opening a pull request.<br />
When opening a pull request, please make sure that:

- The pull request has a clear title;
- The pull request does not consist of too many (unnecessary/small) commits;
- The [StyleCI](https://github.styleci.io/repos/470917304) analysis pass;
- The PHPUnit tests pass.


## ToDo's

- [ ] Add test for formatting request when making an request to an URL without path (e.g. to https://google.com instead of to https://google.com/).
- [ ] Add test for hiding and not-hiding sensitive headers when using the `HttpFormatterMiddleware`.
- [ ] Add cURL formatter.