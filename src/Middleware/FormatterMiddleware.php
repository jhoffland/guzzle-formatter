<?php

namespace GuzzleFormatter\Middleware;

abstract class FormatterMiddleware
{
    private string $filePath;

    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;
    }

    public function writeToFile(string $formatted, $type): void
    {
        $file = fopen($this->filePath, 'a');

        $data = sprintf('--- %s %s ---', date('c'), $type);
        $data .= PHP_EOL . PHP_EOL;
        $data .= $formatted;
        $data .= PHP_EOL . PHP_EOL;

        fwrite($file, $data);

        fclose($file);
    }
}