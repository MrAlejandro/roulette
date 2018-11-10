<?php

namespace App\Printers;

class BatchFilePrinter implements Printer
{
    const FLUSH_LINES_THRESHOLD = 10000;

    protected $lines = [];
    protected $handler;

    public function __construct(string $filePath)
    {
        if (!file_exists(dirname($filePath))) {
            mkdir(dirname($filePath), 0755, true);
        }
        $this->handler = fopen($filePath, 'w');
    }

    public function printHeader(string $header): void
    {
        $this->printLine($header);
    }

    public function printLine(string $line): void
    {
        fwrite($this->handler, $line . PHP_EOL);
    }

    public function printLines(Iterable $lines): void
    {
        $linesNumber = 0;
        foreach ($lines as $line) {
            $this->lines[] = $line;
            if ($linesNumber++ > self::FLUSH_LINES_THRESHOLD) {
                $linesNumber = 0;
                $this->flush();
            }
        }
        $this->flush();
    }

    protected function flush(): void
    {
        if (empty($this->lines)) {
            return;
        }

        $this->lines[] = '';
        fwrite($this->handler, implode(PHP_EOL, $this->lines));
        $this->lines = [];
    }

    public function __destruct()
    {
        $this->flush();
        fclose($this->handler);
    }
}
