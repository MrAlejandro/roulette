<?php

namespace App\Printers;

interface Printer
{
    public function printLine(string $line): void;
    public function printLines(Iterable $lines): void;
}
