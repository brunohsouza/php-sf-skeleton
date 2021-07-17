<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\ImportEmployeeService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use function _HumbugBoxda2413717501\RingCentral\Psr7\mimetype_from_filename;

#[AsCommand(
    name: 'payslip:import-employees',
    description: 'Command to import employees to the database',
)]
class PayslipImportEmployeesCommand extends Command
{
    public function __construct(
        private ImportEmployeeService $importEmployeeService
    )
    {
        parent::__construct();
    }

    protected function configure()
    {
        $this->addArgument(
            'filepath',
            InputArgument::REQUIRED,
            'Path where the file to import is located. e.g.: ./file/path/filename.csv'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $filepath = $this->getArgument($input, $io);

        if (false === $this->validateFilepath($filepath, $io) || false === $this->validateFileType($filepath, $io)) {
            return Command::FAILURE;
        }

        try {
            $this->importEmployeeService->import($filepath);
        } catch (\Exception $exception) {
            $io->error($exception->getMessage());

            return Command::FAILURE;
        }

        $io->success('Employees data were imported with success');

        return Command::SUCCESS;
    }

    private function getArgument(InputInterface $input, SymfonyStyle $io): string|int
    {
        if (!$filepath = $input->getArgument('filepath')) {
            $io->error('The filepath is required to do the import');

            return Command::FAILURE;
        }

        return $filepath;
    }

    private function validateFilepath(string $filepath, SymfonyStyle $io): bool
    {
        if (!file_exists($filepath)) {
            $io->error(sprintf('The filepath passed {{ %s }} do not exists', $filepath));
            return false;
        }

        return true;
    }

    private function validateFileType(string $filepath, SymfonyStyle $io): bool
    {
        if ('text/plain' !== mime_content_type($filepath)) {
            $io->error(sprintf('The file extension {{ %s }} passed do not exists', filetype($filepath)));
            return false;
        }

        return true;
    }
}
