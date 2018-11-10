<?php

namespace App\Printers;

interface Printer
{
    public function printHeader(string $header): void;
    public function printLine(string $line): void;
    public function printLines(Iterable $lines): void;
}
