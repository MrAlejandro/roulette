<?php

namespace App\Generators;

class PartialArrangementVariantsGenerator implements VariantsGenerator
{
    protected $part;
    protected $parts;

    public function __construct(int $part, int $parts)
    {
        $this->part = $part;
        $this->parts = $parts;
    }

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
        [$initialNumber, $upperBound] = $this->calculateInitialNumberAndUpperBound($places);
        $nextBinary = $this->getNextBinaryWithNumberOfBitsSet($initialNumber, $itemsNumber);

        while ($nextBinary < $upperBound) {
            yield $this->getStringFromBinaryAugmentedWithZeroesFromLeft($places, $nextBinary);
            $nextBinary = $this->getNextBinaryWithSameNumberOfBitsSet($nextBinary);
        }
    }

    protected function calculateInitialNumberAndUpperBound(int $places): array
    {
        $maxBinaryInt = 2 ** $places;
        $step = ceil($maxBinaryInt / $this->parts);
        $initialNumber = ($this->part - 1) * $step;
        $upperBound = min($initialNumber + $step, $maxBinaryInt);

        return [$initialNumber, $upperBound];
    }

    protected function getNextBinaryWithNumberOfBitsSet(int $start, int $numOfBits)
    {
        $next = $start;
        while ($this->getSetBitsQuantity($next) !== $numOfBits) {
            $next++;
        }

        return $next;
    }

    protected function getSetBitsQuantity($num) {

        $qty = 0;
        while ($num) {
            $qty += ($num & 1);
            $num = $num >> 1;
        }

        return $qty;
    }

    protected function getStringFromBinaryAugmentedWithZeroesFromLeft(int $strLength, int $nextBinary): string
    {
        return str_pad(strval(decbin($nextBinary)), $strLength, '0', STR_PAD_LEFT);
    }

    protected function getNextBinaryWithSameNumberOfBitsSet($num): int
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