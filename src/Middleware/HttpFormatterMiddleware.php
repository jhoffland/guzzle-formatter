<?php

namespace GuzzleFormatter\Middleware;

use GuzzleFormatter\Formatter;
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
    private bool $hideSensitiveHeaders;

    public function __construct(string $filePath, bool $hideSensitiveHeaders = true)
    {
        parent::__construct($filePath);

        $this->hideSensitiveHeaders = $hideSensitiveHeaders;
    }

    public function requests(): callable
    {
        $requestFormatter = new RequestFormatter(Formatter::DEFAULT_EOL);

        return Middleware::mapRequest(function (RequestInterface $request) use ($requestFormatter) {
            $this->writeToFile($requestFormatter->http($request, $this->hideSensitiveHeaders), 'REQUEST');

            return $request;
        });
    }

    public function responses(): callable
    {
        $responseFormatter = new ResponseFormatter(Formatter::DEFAULT_EOL);

        return Middleware::mapResponse(function (ResponseInterface $response) use ($responseFormatter) {
            $this->writeToFile($responseFormatter->http($response, $this->hideSensitiveHeaders), 'RESPONSE');

            return $response;
        });
    }
}
