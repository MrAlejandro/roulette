<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\Generators\LazyCombinationGenerator;
use App\Generators\LazyBinaryCombinationGenerator;

class LazyCombinationGeneratorTest extends TestCase
{
    /** @var  LazyCombinationGenerator */
    protected $combinator;

    public function setUp()
    {
        $this->combinator = new LazyCombinationGenerator(
            new LazyBinaryCombinationGenerator()
        );
    }

    public function testSetOfEqualSizeToPlaces()
    {
        $combinations = $this->combinator->generate($this->getSetOfNSequentialItems(10), 10);
        $expected = [
            [1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
        ];
        $this->checkCombinations($combinations, $expected);
    }

    public function testOneItemOnTwoPlaces()
    {
        $combinationsGenerator = $this->combinator->generate($this->getSetOfNSequentialItems(2), 1);
        $expected = [
            [1],
            [2],
        ];
        $this->checkCombinations($combinationsGenerator, $expected);
    }

    public function testFiveItemsOnThreePlaces()
    {
        $combinationsGenerator = $this->combinator->generate($this->getSetOfNSequentialItems(5), 3);
        $expected = [
            [1, 2, 3],
            [1, 2, 4],
            [1, 2, 5],
            [1, 3, 4],
            [1, 3, 5],
            [1, 4, 5],
            [2, 3, 4],
            [2, 3, 5],
            [2, 4, 5],
            [3, 4, 5],
        ];
        $this->checkCombinations($combinationsGenerator, $expected);
    }

    public function testNegativePlacesValue()
    {
        $combinations = $this->combinator->generate($this->getSetOfNSequentialItems(0), -3);
        $this->assertEquals([], $combinations);
    }

    public function testEmptySetOnThreePlaces()
    {
        $combinations = $this->combinator->generate($this->getSetOfNSequentialItems(0), 3);
        $this->assertEquals([], $combinations);
    }

    public function testSetOfThreeItemsOnZeroPlaces()
    {
        $combinations = $this->combinator->generate($this->getSetOfNSequentialItems(3), 0);
        $this->assertEquals([], $combinations);
    }

    public function testSetThatLessThanPlacesQuantity()
    {
        $combinations = $this->combinator->generate($this->getSetOfNSequentialItems(10), 11);
        $this->assertEquals([], $combinations);
    }

    /**
     * @dataProvider combinationsQuantityProvider
     */
    public function testAvailableCombinationsQuantity($expected, $setSize, $places)
    {
        $this->assertEquals($expected, $this->combinator->countCombinationsAvailable($setSize, $places));
    }

    public function combinationsQuantityProvider()
    {
        return [
            [0, 0, 1],
            [0, 10, 0],
            [0, 10, -7],
            [1, 999999, 999999],
            [10, 5, 3],
            [9075135300, 36, 18],
        ];
    }

    protected function getSetOfNSequentialItems(int $num)
    {
        return $num === 0 ? [] : range(1, $num);
    }

    protected function checkCombinations($combinationsGenerator, $expected): void
    {
        $combinations = [];
        foreach ($combinationsGenerator as $combination) {
            $combinations[] = $combination;
        }
        $ignoreItemsOrder = true;
        $this->assertEquals($expected, $combinations, '', 0.0, 10, $ignoreItemsOrder);
    }
}