<?php

declare(strict_types=1);

namespace App\Command;

use App\Application\Infrastructure\Connector\AbstractConnector;
use App\Application\Infrastructure\Connector\Connector;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'ben:final-constant',
    description: 'Add a short description for your command',
)]
class BenFinalConstantCommand extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->info('Final Constant: ' . AbstractConnector::TRANSACTION_ID_HEADER);

        $io->choice('If I try to overwrite a final constant what type of error should I receive?',
            ['notice', 'warning', 'fatal']
        );
        $io->info('Trying to overwrite final constant:' . Connector::TRANSACTION_ID_HEADER);

        return Command::SUCCESS;
    }
}
