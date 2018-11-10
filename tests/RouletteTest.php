<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\Generators\VariantsGenerator;
use App\Generators\ArrangementVariantsGenerator;
use App\Printers\BatchFilePrinter;
use App\Roulette;

class RouletteTest extends TestCase
{
    /** @var  Roulette */
    protected $roulette;
    /** @var  InMemoryPrinter */
    protected $printer;
    /** @var  VariantsGenerator */
    protected $variantsGenerator;
    protected $printableThreshold = 10;

    public function setUp()
    {
        $this->printer = new InMemoryPrinter();
        $this->variantsGenerator = new ArrangementVariantsGenerator();
        $this->roulette = new Roulette(
            $this->variantsGenerator,
            $this->printer,
            $this->printableThreshold
        );
    }

    public function testPrintForLessThanPrintableThreshold()
    {
        $this->roulette->run(4, 3);
        $this->assertEquals(1, count($this->printer->getPrinted()));

        $isContainThreshold = mb_strpos($this->printer->getPrinted()[0], $this->printableThreshold) !== false;
        $this->assertTrue($isContainThreshold);
    }

    public function testPrintForMoreThanPrintableThreshold()
    {
        $this->roulette->run(5, 3);
        $this->assertEquals(11, count($this->printer->getPrinted()));

        $isContainThreshold = mb_strpos($this->printer->getPrinted()[0], $this->printableThreshold) === 0;
        $this->assertTrue($isContainThreshold);
    }

    public function testPrintForThreeItemsOnFivePlacesInRealFile()
    {
        $outputFilePath = implode(DIRECTORY_SEPARATOR, [__DIR__, 'output', 'variants.txt']);
        $roulette = new Roulette(
            $this->variantsGenerator, new BatchFilePrinter($outputFilePath), $this->printableThreshold
        );

        $roulette->run(5, 3);

        $expectedFilePath = implode(DIRECTORY_SEPARATOR, [__DIR__, 'fixtures', 'three_on_five_places.txt']);

        $expectedAsArray = file($expectedFilePath);
        $actualAsArray = file($outputFilePath);

        $this->assertEquals($expectedAsArray[0], $actualAsArray[0]);

        $ignoreVariantsOrder = true;
        $this->assertEquals($expectedAsArray, $actualAsArray, '', 0.0, 10, $ignoreVariantsOrder);
    }
}
