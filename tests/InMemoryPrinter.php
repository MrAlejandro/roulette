<?php

namespace App\Tests;

use App\Printers\Printer;

class InMemoryPrinter implements Printer
{
    protected $printed = [];

    public function print(string $line): void
    {
        $this->printed[] = $line;
    }

    public function getPrinted()
    {
        return $this->printed;
    }
}