<?php

declare(strict_types=1);

namespace App\Command;

use App\Application\ValueObject\SalesChannelValueObject;
use App\Application\Enum\SalesChannel;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'titan:enum',
    description: 'Add a short description for your command',
)]
class TitanEnumCommand extends Command
{
    protected function configure(): void
    {
        $this->addArgument('salesChannel', InputArgument::REQUIRED, 'Sales channel type');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $salesChannel = $input->getArgument('salesChannel');

        if ($salesChannel) {
            $io->note(sprintf('You passed an argument: %s', ucfirst($salesChannel)));
        }

        try {
            if (false === SalesChannel::validateSalesChannel($salesChannel)) {
                $io->caution('This values does not match with sales channel');
            }
        } catch (\Throwable $exception) {
            $io->error($exception->getMessage());

            return Command::FAILURE;
        }

        $io->success('You have successfully tested enum feature!');

        return Command::SUCCESS;
    }
}
