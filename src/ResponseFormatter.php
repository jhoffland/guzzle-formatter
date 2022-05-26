<?php

namespace GuzzleFormatter;

use Psr\Http\Message\ResponseInterface;

class ResponseFormatter extends Formatter
{
    public function http(ResponseInterface $response, bool $hideSensitiveHeaders = true): string
    {
        return $this->formatHttpMessage($response, $this->startLine($response), $hideSensitiveHeaders);
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
