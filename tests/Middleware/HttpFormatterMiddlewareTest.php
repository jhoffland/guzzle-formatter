<?php

namespace GuzzleFormatter\Tests\Middleware;

use GuzzleFormatter\Middleware\HttpFormatterMiddleware;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class HttpFormatterMiddlewareTest extends TestCase
{
    public function testRequest()
    {
        $resultFilePath = $this->filePathInDirectory(sprintf('http-request-%u.txt', time()), true);
        $middleware = new HttpFormatterMiddleware($resultFilePath);

        $mockHandler = new MockHandler([new Response(200)]);
        $handlerStack = HandlerStack::create($mockHandler);
        $handlerStack->after('prepare_body', $middleware->requests());

        $client = new Client([
            'handler' => $handlerStack,
            'verify' => false
        ]);
        
        $client->post('https://www.google.com/search', [
            'json' => [
                'type' => 'test'
            ]
        ]);

        $this->assertStringMatchesFormatFile(
            $this->filePathInDirectory('expected-http-request.txt', false),
            file_get_contents($resultFilePath)
        );

        unlink($resultFilePath);
    }

    public function testResponses()
    {
        $resultFilePath = $this->filePathInDirectory(sprintf('http-responses-%u.txt', time()), true);
        $middleware = new HttpFormatterMiddleware($resultFilePath);

        $mockHandler = new MockHandler([
            new Response(201),
            new Response(400, ['Content-Length' => 13], 'Unknown page.')
        ]);
        $handlerStack = HandlerStack::create($mockHandler);
        $handlerStack->after('allow_redirects', $middleware->responses());

        $client = new Client([
            'handler' => $handlerStack,
            'verify' => false
        ]);

        $client->post('https://www.google.com/search', [
            'json' => [
                'type' => 'test'
            ]
        ]);

        try {
            $client->get('https://www.google.com/search?q=guzzle+php');
        } catch (ClientException $exception) {
        }

        $this->assertStringMatchesFormatFile(
            $this->filePathInDirectory('expected-http-responses.txt', false),
            file_get_contents($resultFilePath)
        );

        unlink($resultFilePath);
    }

    private function filePathInDirectory(string $path, bool $actual): string
    {
        return sprintf('%s/results/%s%s', __DIR__, $actual ? 'actual/' : '', $path);
    }
}
