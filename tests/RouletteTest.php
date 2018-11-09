<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\Generators\LazyBinaryCombinationGenerator;
use App\Roulette;

class RouletteTest extends TestCase
{
    /** @var  Roulette */
    protected $roulette;
    /** @var  InMemoryPrinter */
    protected $printer;
    protected $printableThreshold = 10;

    public function setUp()
    {
        $this->printer = new InMemoryPrinter();
        $this->roulette = new Roulette(
            new LazyBinaryCombinationGenerator(),
            $this->printer,
            $this->printableThreshold
        );
    }

    public function testPrintForLessThanPrintableThreshold()
    {
        $this->roulette->execute(4, 3);
        $this->assertEquals(1, count($this->printer->getPrinted()));
        $containsThreshold = mb_strpos($this->printer->getPrinted()[0], $this->printableThreshold) !== false;
        $this->assertTrue($containsThreshold);
    }

    public function testPrintForMoreThanPrintableThreshold()
    {
        $this->roulette->execute(5, 3);
        $this->assertEquals(11, count($this->printer->getPrinted()));
    }
}
