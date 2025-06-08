<?php

// qard-api/src/Command/SyncUsersCommand.php
namespace App\Command;

use App\Service\QardImporterService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:sync-users',
    description: 'Synchronise les utilisateurs depuis l\'API Qard',
)]
class SyncUsersCommand extends Command
{
    private QardImporterService $importer;

    public function __construct(QardImporterService $importer)
    {
        parent::__construct();
        $this->importer = $importer;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->importer->importUsers();
        $output->writeln('✔ Utilisateurs synchronisés depuis Qard.');
        return Command::SUCCESS;
    }
}

