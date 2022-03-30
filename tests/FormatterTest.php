<?php

namespace GuzzleFormatter\Tests;

use GuzzleFormatter\RequestFormatter;
use GuzzleHttp\Psr7\Request;
use PHPUnit\Framework\TestCase;

class FormatterTest extends TestCase
{
    public function testEol()
    {
        $eol = "/r/n";

        $requestFormatter = new RequestFormatter($eol);

        $request = new Request(
            'POST',
            'https://www.google.com/search',
            ['Content-Type' => 'application/json', 'Content-Length' => 18],
            "{ \"type\": \"test\" }"
        );

        $formattedRequest = $requestFormatter->http($request);

        $expectedFormat =
            "POST /search HTTP/1.1" . $eol .
            "Host: www.google.com" . $eol .
            "Content-Type: application/json" . $eol .
            "Content-Length: 18" . $eol .
            $eol .
            "{ \"type\": \"test\" }";

        $this->assertSame($expectedFormat, $formattedRequest);
    }

}
