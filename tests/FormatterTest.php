<?php

namespace GuzzleFormatter\Tests;

use GuzzleFormatter\RequestFormatter;
use GuzzleHttp\Psr7\Request;
use PHPUnit\Framework\TestCase;

class FormatterTest extends TestCase
{
    const DEFAULT_EOL = "\r\n";

    public function testEol()
    {
        $eol = PHP_EOL;

        $requestFormatter = new RequestFormatter($eol);

        $request = new Request(
            'POST',
            'https://www.google.com/search',
            ['Content-Type' => 'application/json', 'Content-Length' => 18],
            "{ \"type\": \"test\" }"
        );

        $actualFormattedRequest = $requestFormatter->http($request);

        $expectedFormattedRequest =
            "POST /search HTTP/1.1" . $eol .
            "Host: www.google.com" . $eol .
            "Content-Type: application/json" . $eol .
            "Content-Length: 18" . $eol .
            $eol .
            "{ \"type\": \"test\" }";

        $this->assertSame($expectedFormattedRequest, $actualFormattedRequest);
    }
}
