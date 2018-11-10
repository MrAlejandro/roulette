<?php

namespace App\Tests;

use App\Printers\Printer;

class InMemoryPrinter implements Printer
{
    protected $printed = [];

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

    public function getPrinted()
    {
        return $this->printed;
    }
}