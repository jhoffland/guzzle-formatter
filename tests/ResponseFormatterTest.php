<?php

namespace GuzzleFormatter\Tests;

use GuzzleFormatter\ResponseFormatter;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class ResponseFormatterTest extends TestCase
{
    public function testHideSensitiveHeadersOfResponse()
    {
        $responseFormatter = new ResponseFormatter();

        $response = new Response(201, ['Cookie' => 'PHPSESSID=789def012']);

        $actualFormattedResponse = $responseFormatter->http($response, true);

        $expectedFormattedResponse =
            "HTTP/1.1 201 Created" . FormatterTest::DEFAULT_EOL .
            "Cookie: [HIDDEN]";

        $this->assertSame($expectedFormattedResponse, $actualFormattedResponse);
    }

    public function testShowSensitiveHeadersOfResponse()
    {
        $responseFormatter = new ResponseFormatter();

        $response = new Response(201, ['Cookie' => 'PHPSESSID=789def012']);

        $actualFormattedResponse = $responseFormatter->http($response, false);

        $expectedFormattedResponse =
            "HTTP/1.1 201 Created" . FormatterTest::DEFAULT_EOL .
            "Cookie: PHPSESSID=789def012";

        $this->assertSame($expectedFormattedResponse, $actualFormattedResponse);
    }
}
