<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'titan:array-unpacking',
    description: 'Simple use case for array unpacking string keys',
)]
class TitanArrayUnpackingCommand extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $product1 = [
            "id" => "1141",
            "amount" => '49.9',
            "currencyCode" => "EUR",
            "reservableType" => "false",
            "country" => "France",
            "stockQuantity" => '21',
            "title" => "Bien-être en duo en Provence",
        ];

        $product2 = [
            "id" => "1143",
            "amount" => "119.9",
            "currencyCode" => "EUR",
            "reservableType" => "true",
            "country" => "France",
            "stockQuantity" => 154,
            "title" => "Mille et une nuits en Normandie",
        ];

        $product3 = [
            "id" => "1145",
            "amount" => "119.9",
            "currencyCode" => "EUR",
            "reservableType" => "true",
            "country" => "France",
            "stockQuantity" => 154,
            "title" => "Mille et une nuits en Normandie",
        ];

        $io->info('Product 1:');
        dump($product1);

        $io->info('Product 2:');
        dump($product2);

        $productMerged = [...['product1' => $product1], ...['product2' => $product2]];

        $io->info('Product Merged:');
        dump($productMerged);

        $io->success('You have successfully tested the array unpacking with string keys feature!');

        return Command::SUCCESS;
    }
}
