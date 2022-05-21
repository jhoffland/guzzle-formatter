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
        $requestFormatter = new RequestFormatter();

        return Middleware::mapRequest(function (RequestInterface $request) use ($requestFormatter) {
            $this->writeToFile($requestFormatter->http($request), 'REQUEST');

            return $request;
        });
    }

    public function responses(): callable
    {
        $responseFormatter = new ResponseFormatter();

        return Middleware::mapResponse(function (ResponseInterface $response) use ($responseFormatter) {
            $this->writeToFile($responseFormatter->http($response), 'RESPONSE');

            return $response;
        });
    }
}
