# Guzzle Formatter

[![Tests status](https://github.com/joephoffland/guzzle-formatter/actions/workflows/testing.yml/badge.svg)](https://github.com/jhoffland/guzzle-formatter/actions/workflows/testing.yml)
[![StyleCI status](https://github.styleci.io/repos/470917304/shield?style=flat&branch=main)](https://github.styleci.io/repos/470917304?branch=main)

PHP library for formatting Guzzle requests and responses to [HTTP messages](https://developer.mozilla.org/en-US/docs/Web/HTTP/Messages).


## Installation

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

## Supported PHP & package versions

Check the `composer.json` file for the supported PHP & package versions.<br />
Feel free to add support for additional versions to this library.


## Contributing

Feel free to contribute to this library. Contribute by forking the [GitHub repository](https://github.com/jhoffland/guzzle-formatter) and opening a pull request.<br />
When opening a pull request, please make sure that:

- The pull request has a clear title;
- The pull request does not consist of too many (unnecessary/small) commits;
- The [StyleCI](https://github.styleci.io/repos/470917304) analysis pass;
- The PHPUnit tests pass.

## ToDo

- [ ] Test and (if currently not working properly) add support for cookies.
- [ ] Add cURL formatter.