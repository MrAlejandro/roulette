<?php

namespace App;

use App\Generators\CombinationGenerator;
use App\Printers\Printer;

class Roulette
{
    const PRINTABLE_QUANTITY = 10;

    protected $combinationGenerator;
    protected $printableThreshold;
    protected $printer;

    public function __construct(
        CombinationGenerator $cg,
        Printer $pr,
        int $printableThreshold = 10
    ) {
        $this->combinationGenerator = $cg;
        $this->printer = $pr;
        $this->printableThreshold = $printableThreshold;
    }

    public function execute(int $fieldsCount, int $chipCount)
    {
        $set = range(0, $fieldsCount - 1);
        $combinationsCount = $this->combinationGenerator->countCombinationsAvailable(
            $fieldsCount, $chipCount
        );

        if ($combinationsCount < $this->printableThreshold) {
            $this->printer->print("менее {$this->printableThreshold} вариантов");
            return;
        }


    }
}