<?php

namespace App\Generators;

class ArrangementVariantsGenerator implements VariantsGenerator
{
    public function generate(int $places, int $itemsNumber): Iterable
    {
        $combinationsQuantity = $this->countAvailableVariants($places, $itemsNumber);
        if ($combinationsQuantity === 1) {
            return [$this->getStringOfNOnes($itemsNumber)];
        } elseif ($combinationsQuantity === 0) {
            return [];
        }

        return $this->getGenerator($places, $itemsNumber);
    }

    public function countAvailableVariants(int $places, int $itemsNumber): int
    {
        if ($places < $itemsNumber || $places <= 0 || $itemsNumber <= 0) {
            return 0;
        } elseif ($itemsNumber === $places) {
            return 1;
        }

        return $this->factorial($places) /
            ($this->factorial($itemsNumber) * $this->factorial($places - $itemsNumber));
    }

    protected function factorial(int $num)
    {
        if ($num < 2) {
            return 1;
        }

        return $num * $this->factorial($num - 1);
    }

    protected function getStringOfNOnes($n): string
    {
        return str_repeat('1', $n);
    }

    protected function getGenerator(int $places, int $itemsNumber)
    {
        $maxBinaryInt = 2 ** $places;
        $nextBinary = bindec($this->getStringOfNOnes($itemsNumber));

        while ($nextBinary < $maxBinaryInt) {
            yield $this->getStringFromBinaryAugmentedWithZeroesFromLeft($places, $nextBinary);
            $nextBinary = $this->getNextBinaryWithSameNumberOfBitsSet($nextBinary);
        }
    }

    protected function getStringFromBinaryAugmentedWithZeroesFromLeft(int $strLength, $nextBinary): string
    {
        return str_pad(strval(decbin($nextBinary)), $strLength, '0', STR_PAD_LEFT);
    }

    protected function getNextBinaryWithSameNumberOfBitsSet($num)
    {
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
    }
}
