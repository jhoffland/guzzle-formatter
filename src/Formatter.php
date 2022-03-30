<?php

namespace GuzzleFormatter;

use Psr\Http\Message\MessageInterface;

abstract class Formatter
{
    protected string $eol;

    public function __construct($eol = PHP_EOL)
    {
        $this->eol = $eol;
    }

    protected function formatHttp(MessageInterface $message, string $startLine): string
    {
        $httpMessage = $startLine;
        $httpMessage .= $this->eol;
        $httpMessage .= $this->headers($message);

        $body = $this->body($message);
        if (! is_null($body)) {
            $httpMessage .= $this->eol . $this->eol;
            $httpMessage .= $body;
        }

        return $httpMessage;
    }

    private function headers(MessageInterface $message): string
    {
        $headerLines = [];

        foreach ($message->getHeaders() as $name => $values) {
            foreach ($values as $value) {
                $headerLines[] = sprintf('%s: %s', $name, $value);
            }
        }

        return implode($this->eol, $headerLines);
    }

    private function body(MessageInterface $message): ?string
    {
        $body = $message->getBody();

        if ($body->isSeekable()) {
            $previousPosition = $body->tell();
            $body->rewind();
        }

        $bodyContent = $body->getContents();

        if ($body->isSeekable()) {
            $body->seek($previousPosition);
        }

        return strlen($bodyContent) > 0 ? $bodyContent : null;
    }
}