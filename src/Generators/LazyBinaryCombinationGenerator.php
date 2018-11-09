<?php

namespace App\Generators;

class LazyBinaryCombinationGenerator implements CombinationGenerator
{
    public function generate(array $set, int $places): Iterable
    {
        $setSize = count($set);
        $combinationsQuantity = $this->countCombinationsAvailable($setSize, $places);
        if ($combinationsQuantity === 1) {
            return [str_repeat('1', $setSize)];
        } elseif ($combinationsQuantity === 0) {
            return [];
        }

        return $this->getGenerator($set, $places);
    }

    public function countCombinationsAvailable(int $setSize, int $places): int
    {
        if ($setSize < $places || $places <= 0) {
            return 0;
        } elseif ($setSize === $places) {
            return 1;
        }

        return $this->factorial($setSize) / ($this->factorial($places) * $this->factorial($setSize - $places));
    }

    protected function factorial(int $num)
    {
        if ($num < 2) {
            return 1;
        }

        return $num * $this->factorial($num - 1);
    }

    protected function getGenerator(array $set, int $places)
    {
        $setSize = count($set);
        $maxBinaryInt = 2 ** $setSize;
        $nextBinary = bindec(str_repeat('1', $places));

        $getNextWithSameNumberOfBitsSet = function ($num) {
            $next = 0;
            if ($num) {
                $rightmostBit = $num & (-$num);
                $nextHigherOneBit = $num + $rightmostBit;
                $rightOnesPattern = $num ^ $nextHigherOneBit;
                $rightOnesPattern = (int) ($rightOnesPattern / $rightmostBit);
                $rightOnesPattern = $rightOnesPattern >> 2;
                $next = $nextHigherOneBit | $rightOnesPattern;
            }

            return $next;
        };

        while ($nextBinary < $maxBinaryInt) {
            yield array_pad(
                str_split((string) decbin($nextBinary)),
                -$setSize,
                '0'
            );
            $nextBinary = $getNextWithSameNumberOfBitsSet($nextBinary);
        }
    }
}
