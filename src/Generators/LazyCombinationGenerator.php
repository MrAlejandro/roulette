<?php

namespace App\Generators;

class LazyCombinationGenerator implements CombinationGenerator
{
    protected $combinationGenerator;

    public function __construct(LazyBinaryCombinationGenerator $cg)
    {
        $this->combinationGenerator = $cg;
    }

    public function generate(array $set, int $places): Iterable
    {
        $combinations = $this->combinationGenerator->generate($set, $places);
        if (empty($combinations)) {
            return [];
        }

        return $this->getGenerator($set, $combinations);
    }

    protected function getGenerator(array $set, Iterable $combinations): Iterable
    {
        foreach ($combinations as $binaryCombination) {
            yield array_values(
                array_filter($set, function ($key) use ($binaryCombination) {
                    return !empty($binaryCombination[$key]);
                }, ARRAY_FILTER_USE_KEY)
            );
        }
    }

    public function countCombinationsAvailable(int $setSize, int $places): int
    {
        return $this->combinationGenerator->countCombinationsAvailable($setSize, $places);
    }
}
