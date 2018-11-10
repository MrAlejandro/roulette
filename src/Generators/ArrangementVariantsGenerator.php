<?php

namespace App\Generators;

class ArrangementVariantsGenerator extends PartialArrangementVariantsGenerator implements VariantsGenerator
{
    public function __construct()
    {
        parent::__construct(0, 0);
    }
}
