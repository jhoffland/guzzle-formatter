<?php

namespace GuzzleFormatter;

use Psr\Http\Message\RequestInterface;

class RequestFormatter extends Formatter
{
    public function http(RequestInterface $request, bool $hideSensitiveHeaders = true): string
    {
        return $this->formatHttpMessage($request, $this->startLine($request), $hideSensitiveHeaders);
    }

    private function startLine(RequestInterface $request): string
    {
        return sprintf('%s %s HTTP/%s',
            $request->getMethod(),
            $this->requestTarget($request),
            $request->getProtocolVersion()
        );
    }

    private function requestTarget(RequestInterface $request): string
    {
        $uri = $request->getUri();

        $path = $uri->getPath();
        $query = $uri->getQuery();

        $query = $query ? '?' . $query : '';

        return $path . $query;
    }
}
