<?php

namespace GuzzleFormatter;

use Psr\Http\Message\ResponseInterface;

class ResponseFormatter extends Formatter
{
    public function http(ResponseInterface $response): string
    {
        return $this->formatHttp($response, $this->startLine($response));
    }

    private function startLine(ResponseInterface $response): string
    {
        return sprintf('HTTP/%s %s %s',
            $response->getProtocolVersion(),
            $response->getStatusCode(),
            $response->getReasonPhrase()
        );
    }
}
