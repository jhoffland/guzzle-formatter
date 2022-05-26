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

                "GET /search?q=guzzle+php HTTP/1.1" . FormatterTest::DEFAULT_EOL .
                "Host: www.google.com" . FormatterTest::DEFAULT_EOL .
                "User-Agent: GuzzleFormatterTest/1.0",
            ],
            [
                new Request('HEAD', 'https://www.google.com/search#test', []),

                "HEAD /search HTTP/1.1" . FormatterTest::DEFAULT_EOL .
                "Host: www.google.com",
            ],
            [
                new Request('POST', 'https://www.google.com/search',
                    ['Content-Type' => 'application/json', 'Content-Length' => 18],
                    "{ \"type\": \"test\" }"),

                "POST /search HTTP/1.1" . FormatterTest::DEFAULT_EOL .
                "Host: www.google.com" . FormatterTest::DEFAULT_EOL .
                "Content-Type: application/json" . FormatterTest::DEFAULT_EOL .
                "Content-Length: 18" . FormatterTest::DEFAULT_EOL .
                FormatterTest::DEFAULT_EOL .
                "{ \"type\": \"test\" }",
            ],
        ];
    }

    public function testHideSensitiveHeaders()
    {
        $requestFormatter = new RequestFormatter();

        $request = new Request('GET', 'https://www.google.com/search?q=guzzle+php', [
            'User-Agent' => 'GuzzleFormatterTest/1.0',
            'Authorization' => 'Basic R3V6emxlRm9ybWF0dGVyOlRlc3Q=',
            'Set-Cookie' => 'PHPSESSID=123abc456; Max-Age=7200; HttpOnly; SameSite=Lax',
        ]);

        $actualFormattedRequest = $requestFormatter->http($request, true);

        $expectedFormattedRequest =
            "GET /search?q=guzzle+php HTTP/1.1" . FormatterTest::DEFAULT_EOL .
            "Host: www.google.com" . FormatterTest::DEFAULT_EOL .
            "User-Agent: GuzzleFormatterTest/1.0" . FormatterTest::DEFAULT_EOL .
            "Authorization: [HIDDEN]" . FormatterTest::DEFAULT_EOL .
            "Set-Cookie: [HIDDEN]";

        $this->assertSame($expectedFormattedRequest, $actualFormattedRequest);
    }

    public function testShowSensitiveHeaders()
    {
        $requestFormatter = new RequestFormatter();

        $request = new Request('GET', 'https://www.google.com/search?q=guzzle+php', [
            'Authorization' => 'Basic R3V6emxlRm9ybWF0dGVyOlRlc3Q=',
        ]);

        $actualFormattedRequest = $requestFormatter->http($request, false);

        $expectedFormattedRequest =
            "GET /search?q=guzzle+php HTTP/1.1" . FormatterTest::DEFAULT_EOL .
            "Host: www.google.com" . FormatterTest::DEFAULT_EOL .
            "Authorization: Basic R3V6emxlRm9ybWF0dGVyOlRlc3Q=";

        $this->assertSame($expectedFormattedRequest, $actualFormattedRequest);
    }
}
