<?php

namespace App\Generators;

interface CombinationGenerator
{
    public function generate(array $set, int $places): Iterable;
    public function countCombinationsAvailable(int $setSize, int $places): int;
}
