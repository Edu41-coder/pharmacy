<?php

namespace App\Command;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class VerifyDatabaseCommand extends Command
{
    protected static $defaultName = 'app:verify-database';

    public function __construct(private EntityManagerInterface $entityManager)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $connection = $this->entityManager->getConnection();
        $tables = $connection->createSchemaManager()->listTableNames();
        
        $output->writeln('Tables trouvées :');
        foreach ($tables as $table) {
            $output->writeln("- $table");
        }

        return Command::SUCCESS;
    }
} 