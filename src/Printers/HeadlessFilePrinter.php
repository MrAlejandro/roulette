<?php

namespace App\Printers;

class HeadlessFilePrinter implements Printer
{
    protected $printer;

    public function __construct(Printer $printer)
    {
        $this->printer = $printer;
    }

    public function printHeader(string $header): void
    {
        // No header for headless printer
    }

    public function printLine(string $line): void
    {
        $this->printer->printLine($line);
    }

    public function printLines(Iterable $lines): void
    {
        $this->printer->printLines($lines);
    }
}