<?php

declare(strict_types=1);

namespace App\Command;

use App\Application\Dto\DiscountDto;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'ben:readonly-properties',
    description: 'Simple use case of readonly properties with DTOs',
)]
class BenReadonlyPropertiesCommand extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->info('Creating the discount class with Black Friday discount');
        $discountDto = new DiscountDto('black-friday', '50');

        dump($discountDto);

        $io->info('Changing the value');
        $discountDto->setValue('25');

        dump($discountDto->getValue());

        $io->info('Trying to change the type');

        try {
            $discountDto->setType('sale');
        } catch (\Throwable $error) {
            $io->error($error->getMessage());
        }

        $io->success('You have successfully tested the readonly properties feature with DTO!');

        return Command::SUCCESS;
    }
}
