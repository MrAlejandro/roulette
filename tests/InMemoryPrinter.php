<?php

namespace App\Tests;

use App\Printers\Printer;

class InMemoryPrinter implements Printer
{
    protected $printed = [];

    public function printHeader(string $header): void
    {
        $this->printLine($header);
    }

    public function printLine(string $line): void
    {
        $this->printed[] = $line;
    }

    public function printLines(Iterable $lines): void
    {
        foreach ($lines as $line) {
            $this->printLine($line);
        }
    }

    public function getPrinted(): array
    {
        return $this->printed;
    }
}