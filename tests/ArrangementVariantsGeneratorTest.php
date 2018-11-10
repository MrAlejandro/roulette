<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\Generators\VariantsGenerator;
use App\Generators\ArrangementVariantsGenerator;

class ArrangementVariantsGeneratorTest extends TestCase
{
    /** @var VariantsGenerator */
    protected $generator;

    public function setUp()
    {
        $this->generator = new ArrangementVariantsGenerator();
    }

    public function testItemsEqualsNumberOfPlaces()
    {
        $variants = $this->generator->generate(10, 10);
        $expected = ['1111111111'];
        $this->checkArrangementVariants($variants, $expected);
    }

    public function testOneItemOnTwoPlaces()
    {
        $variants = $this->generator->generate(2, 1);
        $expected = ['01', '10'];
        $this->checkArrangementVariants($variants, $expected);
    }

    public function testThreeItemsOnThreeFive()
    {
        $variants = $this->generator->generate(5, 3);
        $expected = [
            '00111', '01011', '01101', '01110', '10011',
            '10101', '10110', '11001', '11010', '11100',
        ];
        $this->checkArrangementVariants($variants, $expected);
    }

    public function testNegativePlacesValue()
    {
        $variants = $this->generator->generate(-3, 1);
        $this->checkArrangementVariants($variants, []);
    }

    public function testZeroItemsOnThreePlaces()
    {
        $variants = $this->generator->generate(3, 0);
        $this->checkArrangementVariants($variants, []);
    }

    public function testThreeItemsOnZeroPlaces()
    {
        $variants = $this->generator->generate(0, 3);
        $this->checkArrangementVariants($variants, []);
    }

    public function testNumberOfItemsLessThanNumberOfPlaces()
    {
        $variants = $this->generator->generate(10, 11);
        $this->checkArrangementVariants($variants, []);
    }

    /**
     * @dataProvider combinationsQuantityProvider
     */
    public function testAvailableCombinationsQuantity($expected, $places, $itemsNumber)
    {
        $this->assertEquals($expected, $this->generator->countAvailableVariants($places, $itemsNumber));
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

    protected function checkArrangementVariants($variantsGenerator, $expected): void
    {
        $variants = [];
        foreach ($variantsGenerator as $variant) {
            $variants[] = $variant;
        }
        $ignoreVariantsOrder = true;
        $this->assertEquals($expected, $variants, '', 0.0, 10, $ignoreVariantsOrder);
    }
}