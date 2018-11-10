<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use App\Roulette;
use App\Generators\ArrangementVariantsGenerator;
use App\Printers\BatchFilePrinter;

class RouletteCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('roulette')
            ->setDescription('Roulette chips arrangement variants generator')
            ->addArgument(
                'fieldsCount',
                InputArgument::REQUIRED,
                'The number of fields on the roulette'
            )
            ->addArgument(
                'chipCount',
                InputArgument::REQUIRED,
                'The number of chips to be arranged in unique way'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $start = time();
        $fieldsCount = (int) $input->getArgument('fieldsCount');
        $chipCount = (int) $input->getArgument('chipCount');

        $targetFilePath = implode(
            DIRECTORY_SEPARATOR,
            [__DIR__, '..', '..', 'var', "variants_{$fieldsCount}_{$chipCount}.txt"]
        );
        $roulette = new Roulette(
            new ArrangementVariantsGenerator(),
            new BatchFilePrinter($targetFilePath)
        );
        $roulette->run($fieldsCount, $chipCount);

        $end = time();
        $output->writeln(sprintf('Time spent: %d sec', $end - $start));
    }
}
