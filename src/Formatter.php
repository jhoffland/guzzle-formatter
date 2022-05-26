<?php

namespace GuzzleFormatter;

use Psr\Http\Message\MessageInterface;

abstract class Formatter
{
    public const DEFAULT_EOL = "\r\n";

    private const SENSITIVE_HEADERS = ['authorization', 'set-cookie', 'cookie'];

    protected string $eol;

    public function __construct(string $eol = self::DEFAULT_EOL)
    {
        $this->eol = $eol;
    }

    protected function formatHttpMessage(
        MessageInterface $message,
        string $startLine,
        bool $hideSensitiveHeaders
    ): string {
        $httpMessage = $startLine;
        $httpMessage .= $this->eol;
        $httpMessage .= $this->headers($message, $hideSensitiveHeaders);

        $body = $this->body($message);
        if (! is_null($body)) {
            $httpMessage .= $this->eol . $this->eol;
            $httpMessage .= $body;
        }

        return $httpMessage;
    }

    private function headers(MessageInterface $message, bool $hideSensitiveHeaders): string
    {
        $headerLines = [];

        foreach ($message->getHeaders() as $name => $values) {
            foreach ($values as $value) {
                if ($hideSensitiveHeaders && in_array(strtolower($name), self::SENSITIVE_HEADERS)) {
                    $value = '[HIDDEN]';
                }

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
