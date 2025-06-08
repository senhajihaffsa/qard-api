<?php

namespace App\Command;

use App\Service\QardApiService;
use App\Repository\UserRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(
    name: 'app:scan-users-with-data',
    description: 'Liste les utilisateurs avec données disponibles (profil, dirigeants, états financiers).'
)]
class ScanUsersWithDataCommand extends Command
{
    public function __construct(
        private QardApiService $api,
        private UserRepository $userRepo
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $users = $this->userRepo->findAll();
        $found = 0;

        foreach ($users as $user) {
            $userId = $user->getId();
            $output->writeln("🔎 Scanning user: {$user->getName()} ({$userId})");

            $profile = $this->api->getUserProfile($userId);
            $officers = $this->api->getCompanyOfficers($userId);
            $financials = $this->api->getCompanyFinancials($userId);

            // Log API raw responses (truncated to 300 chars if large)
            $output->writeln("   ➤ Profile response: " . substr(json_encode($profile), 0, 300));
            $output->writeln("   ➤ Officers response: " . substr(json_encode($officers), 0, 300));
            $output->writeln("   ➤ Financials response: " . substr(json_encode($financials), 0, 300));

            $hasProfile = !empty($profile);
            $hasOfficers = isset($officers['result']) && is_array($officers['result']) && count($officers['result']) > 0;
            $hasFinancials = isset($financials['result']) && is_array($financials['result']) && count($financials['result']) > 0;

            if ($hasProfile || $hasOfficers || $hasFinancials) {
                $found++;
                $output->writeln("✅ {$user->getName()} ({$userId}) => Data found:");
                if ($hasProfile) $output->writeln("   - 📄 Profile");
                if ($hasOfficers) $output->writeln("   - 👥 Officers (" . count($officers['result']) . ")");
                if ($hasFinancials) $output->writeln("   - 📈 Financials (" . count($financials['result']) . ")");
            } else {
                $output->writeln("⚠️ Aucun contenu utile trouvé pour cet utilisateur.");
            }

            $output->writeln(str_repeat('-', 40));
        }

        $output->writeln("✔️ Total utilisateurs avec données : $found / " . count($users));
        return Command::SUCCESS;
    }
}
