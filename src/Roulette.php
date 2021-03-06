<?php

namespace App;

use App\Generators\VariantsGenerator;
use App\Printers\Printer;

class Roulette
{
    protected $variantsGenerator;
    protected $printableThreshold;
    protected $printer;

    public function __construct(
        VariantsGenerator $cg,
        Printer $pr,
        int $printableThreshold = 10
    ) {
        $this->variantsGenerator = $cg;
        $this->printer = $pr;
        $this->printableThreshold = $printableThreshold;
    }

    public function run(int $fieldsCount, int $chipCount): void
    {
        $variantsCount = $this->variantsGenerator->countAvailableVariants(
            $fieldsCount, $chipCount
        );

        if ($variantsCount < $this->printableThreshold) {
            $this->printer->printHeader("менее {$this->printableThreshold} вариантов");
            return;
        }

        $this->printer->printHeader($variantsCount);
        $arrangementVariants = $this->variantsGenerator->generate($fieldsCount, $chipCount);
        $this->printer->printLines($arrangementVariants);
    }
}