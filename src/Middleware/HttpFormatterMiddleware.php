<?php

namespace GuzzleFormatter\Middleware;

use GuzzleFormatter\RequestFormatter;
use GuzzleFormatter\ResponseFormatter;
use GuzzleHttp\Middleware;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Create Guzzle middleware that saves the requests and/or responses as an HTTP message to a file.
 */
class HttpFormatterMiddleware extends FormatterMiddleware
{
    public function requests(): callable
    {
        return fn(callable $handler) => function (RequestInterface $request, array $options) use ($handler) {
            $requestFormatter = new RequestFormatter();
            $this->writeToFile($requestFormatter->http($request), 'REQUEST');

            return $handler($request, $options);
        };
    }

    public function responses(): callable
    {
        return Middleware::mapResponse(function (ResponseInterface $response) {
            $responseFormatter = new ResponseFormatter();
            $this->writeToFile($responseFormatter->http($response), 'RESPONSE');

            return $response;
        });
    }
}
