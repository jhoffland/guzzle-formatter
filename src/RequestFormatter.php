<?php

namespace GuzzleFormatter;

use Psr\Http\Message\RequestInterface;

class RequestFormatter extends Formatter
{
    public function http(RequestInterface $request): string
    {
        return $this->formatHttp($request, $this->startLine($request));
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
