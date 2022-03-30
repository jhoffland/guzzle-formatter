<?php

namespace GuzzleFormatter\Tests;

use GuzzleFormatter\RequestFormatter;
use GuzzleHttp\Psr7\Request;
use PHPUnit\Framework\TestCase;

class RequestFormatterTest extends TestCase
{
    /**
     * @dataProvider httpProvider
     */
    public function testHttp(Request $request, string $expectedResult)
    {
        $requestFormatter = new RequestFormatter();
        $formattedRequest = $requestFormatter->http($request);

        $this->assertSame($expectedResult, $formattedRequest);
    }

    public function httpProvider(): array
    {
        return [
            [
                new Request('GET', 'https://www.google.com/search?q=guzzle+php',
                    ['User-Agent' => 'GuzzleFormatterTest/1.0']),

                "GET /search?q=guzzle+php HTTP/1.1" . PHP_EOL .
                "Host: www.google.com" . PHP_EOL .
                "User-Agent: GuzzleFormatterTest/1.0",
            ],
            [
                new Request('HEAD', 'https://www.google.com/search#test', []),

                "HEAD /search HTTP/1.1" . PHP_EOL .
                "Host: www.google.com",
            ],
            [
                new Request('POST', 'https://www.google.com/search',
                    ['Content-Type' => 'application/json', 'Content-Length' => 18],
                    "{ \"type\": \"test\" }"),

                "POST /search HTTP/1.1" . PHP_EOL .
                "Host: www.google.com" . PHP_EOL .
                "Content-Type: application/json" . PHP_EOL .
                "Content-Length: 18" . PHP_EOL .
                PHP_EOL .
                "{ \"type\": \"test\" }",
            ],
        ];
    }

}
