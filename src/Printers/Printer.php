<?php

namespace App\Printers;

interface Printer
{
    public function print(string $line): void;
}
