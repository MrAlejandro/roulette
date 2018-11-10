<?php

namespace App\Generators;

interface VariantsGenerator
{
    public function generate(int $places, int $itemsNumber): Iterable;
    public function countAvailableVariants(int $setSize, int $places): int;
}
