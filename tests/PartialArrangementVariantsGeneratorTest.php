<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\Generators\PartialArrangementVariantsGenerator;

class PartialArrangementVariantsGeneratorTest extends TestCase
{
    protected $partialGenerator;

    public function testThreeItemsOnFivePlacesPartialVariants()
    {
        $this->checkNthOfFivePartsOfThreeItemsOnFivePlacesVariants(1, []);
        $this->checkNthOfFivePartsOfThreeItemsOnFivePlacesVariants(2, ['00111', '01011', '01101']);
        $this->checkNthOfFivePartsOfThreeItemsOnFivePlacesVariants(3, ['01110', '10011']);
        $this->checkNthOfFivePartsOfThreeItemsOnFivePlacesVariants(4, ['10101', '10110', '11001', '11010']);
        $this->checkNthOfFivePartsOfThreeItemsOnFivePlacesVariants(5, ['11100']);
    }

    public function testThreeItemsOnFivePlacesAllAtOncePartialVariants()
    {
        $generator = new PartialArrangementVariantsGenerator(0, 0);
        $variants = $generator->generate(5, 3);
        $expected = [
            '00111', '01011', '01101', '01110', '10011',
            '10101', '10110', '11001', '11010', '11100',
        ];
        $this->checkArrangementVariants($variants, $expected);
    }

    protected function checkNthOfFivePartsOfThreeItemsOnFivePlacesVariants($part, $expected): void
    {
        $parts = 5;
        $generator = new PartialArrangementVariantsGenerator($part, $parts);
        $variants = $generator->generate(5, 3);
        $this->checkArrangementVariants($variants, $expected);
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
