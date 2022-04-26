<?php

namespace GuzzleFormatter\Middleware;

use GuzzleFormatter\Formatter;

abstract class FormatterMiddleware
{
    private string $filePath;

    /**
     * @param  string  $filePath  The path of the file where the formatted HTTP messages will be written to.
     */
    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;
    }

    protected function writeToFile(string $formatted, string $type): void
    {
        $file = fopen($this->filePath, 'a');

        $data = sprintf('--- %s %s ---', date('c'), $type);
        $data .= Formatter::DEFAULT_EOL . Formatter::DEFAULT_EOL;
        $data .= $formatted;
        $data .= Formatter::DEFAULT_EOL . Formatter::DEFAULT_EOL;

        fwrite($file, $data);

        fclose($file);
    }
}
