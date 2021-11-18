<?php

declare(strict_types=1);

namespace App\Command;

use App\Application\Command\CreateSalesOrderCommand;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'titan:readonly-properties',
    description: 'Simple use case example for readonly properties',
)]
class TitanReadonlyPropertiesCommand extends Command
{
    public const NAME = 'titan:readonly-properties';

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $createSalesOrderCommand = new CreateSalesOrderCommand(
            '9999',
            '1234',
            [
                'product' => [
                    'id' => '6',
                    'quantity' => '1',
                    'sellableType' => 'box',
                    'reasonCode' => 'activationSales',
                    'discount' => '0'
                ],
            ],
            'SBX',
            'FR',
            [
                'firstName' => 'Lorem',
                'lastName' => 'Ipsum',
                'postalCode' => 'RTNW-9999',
                'addressLine1' => '9999 Nowhere Road',
                'addressLine2' => 'County Nowhere',
                'city' => 'Nowhere city',
                'country' => 'FR'
            ],
            [
                'firstName' => 'Lorem',
                'lastName' => 'Ipsum',
                'postalCode' => 'RTNW-9999',
                'addressLine1' => '9999 Nowhere Road',
                'addressLine2' => 'County Nowhere',
                'city' => 'Nowhere city',
                'country' => 'FR'
            ],
            [
                'phoneNumber' => '0889879856486',
                'emailAddress' => 'titan@titan.com'
            ]
        );

        var_dump($createSalesOrderCommand);
        var_dump($createSalesOrderCommand->setBrand('BON'));

        $io->success('You have successfully tested the readonly properties feature!');

        return Command::SUCCESS;
    }
}
