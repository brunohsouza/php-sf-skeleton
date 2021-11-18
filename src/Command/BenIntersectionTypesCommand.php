<?php

namespace App\Command;

use App\Application\Exception\ContextualExceptionInterface;
use App\Application\Exception\GatewayException;
use App\Application\Exception\LoggableExceptionInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'ben:intersection-types',
    description: 'Example of intersection types in PHP',
)]
class BenIntersectionTypesCommand extends Command
{
    public const NAME = 'ben:intersection-types';

    private LoggableExceptionInterface&ContextualExceptionInterface $gatewayException;

    public function __construct(GatewayException $gatewayException)
    {
        parent::__construct(self::NAME);
        $this->gatewayException = $gatewayException;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->info('Is GatewayException instance of LoggableExceptionInterface?');

        dump ($this->gatewayException instanceof LoggableExceptionInterface);

        $io->info('Is GatewayException instance of ContextualExceptionInterface?');

        dump ($this->gatewayException instanceof ContextualExceptionInterface);

        $io->success('You have successfully tested the pure intersection types feature!');

        return Command::SUCCESS;
    }
}
