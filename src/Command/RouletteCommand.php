<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use App\Roulette;
use App\Generators\ArrangementVariantsGenerator;
use App\Generators\PartialArrangementVariantsGenerator;
use App\Printers\BatchFilePrinter;
use App\Printers\HeadlessFilePrinter;

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
        $fieldsCount = (int) $input->getArgument('fieldsCount');
        $chipCount = (int) $input->getArgument('chipCount');

        $start = time();
        if ($this->isEligibleForParallelProcessing($fieldsCount, $chipCount)) {
            $this->runParallelProcessing($fieldsCount, $chipCount);
        } else {
            $this->runSingleProcessing($fieldsCount, $chipCount);
        }

        $end = time();
        $output->writeln(sprintf('Time spent: %d sec', $end - $start));
    }

    protected function isEligibleForParallelProcessing($fieldsCount, $chipsCount)
    {
        $delta = $fieldsCount - $chipsCount;
        $makesSenseToParallel = $fieldsCount > 20 && $delta > 5;

        return function_exists('pcntl_fork')
            && $makesSenseToParallel;
    }

    protected function runParallelProcessing(int $fieldsCount, int $chipCount): void
    {
        $parts = $this->getOptimalPartsNumber();
        for ($part = 1; $part <= $parts; $part++) {
            $pid = pcntl_fork();

            if ($pid === 0) {
                $targetFilePath = $this->getTargetFilePath("variants_{$fieldsCount}_{$chipCount}_{$part}.txt");
                $batchPrinter = new BatchFilePrinter($targetFilePath);
                $roulette = new Roulette(
                    new PartialArrangementVariantsGenerator($part, $parts),
                    $part === 1 ? $batchPrinter : new HeadlessFilePrinter($batchPrinter)
                );
                $roulette->run($fieldsCount, $chipCount);
                exit;
            }
        }

        $this->waitUntilAllProcessesFinished();
    }

    protected function getOptimalPartsNumber()
    {
        // TODO: implement some calculation (may be cross-platform cores quantity check)
        return 12;
    }

    protected function getTargetFilePath($targetFileName): string
    {
        return implode(DIRECTORY_SEPARATOR, [__DIR__, '..', '..', 'var', $targetFileName]);
    }

    protected function waitUntilAllProcessesFinished(): void
    {
        $waitForAnyChild = 0;
        $status = null;
        while (pcntl_waitpid($waitForAnyChild, $status) !== -1) {
        }
    }

    protected function runSingleProcessing($fieldsCount, $chipCount): void
    {
        $targetFilePath = $this->getTargetFilePath("variants_{$fieldsCount}_{$chipCount}.txt");
        $roulette = new Roulette(
            new ArrangementVariantsGenerator(),
            new BatchFilePrinter($targetFilePath)
        );
        $roulette->run($fieldsCount, $chipCount);
    }

}
