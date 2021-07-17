<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\ExportEmployeesService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'payslip:export-employees',
    description: 'Export a csv file with employees data',
)]
class PayslipExportCommand extends Command
{
    public function __construct(
        private ExportEmployeesService $exportEmployeesService
    )
    {
        parent::__construct();
    }


    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        try {
            $this->exportEmployeesService->export();
        } catch (\Exception $exception) {
            $io->error(sprintf('Failed to export employees to csv, %s', $exception->getMessage()));

            return Command::FAILURE;
        }

        $io->success('Exported employees with success');

        return Command::SUCCESS;
    }
}
