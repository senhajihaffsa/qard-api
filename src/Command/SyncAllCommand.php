<?php

namespace App\Command;

use App\Service\QardImporterService;
use App\Repository\UserRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(
    name: 'app:sync-all',
    description: 'Synchronise tous les utilisateurs, profils, dirigeants et financiers depuis Qard.'
)]
class SyncAllCommand extends Command
{
    public function __construct(
        private QardImporterService $importer,
        private UserRepository $userRepo
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('<info>Importation des utilisateurs...</info>');
        $this->importer->importUsers();

        $output->writeln('<info>Importation des profils, dirigeants et financiers...</info>');
        $users = $this->userRepo->findAll();

        foreach ($users as $user) {
            $this->importer->importCompanyProfile($user);
            $this->importer->importOfficers($user);       // à créer dans le service
            $this->importer->importFinancials($user);     // à créer dans le service
        }

        $output->writeln('<info>✅ Synchronisation complète depuis Qard terminée.</info>');

        return Command::SUCCESS;
    }
}
