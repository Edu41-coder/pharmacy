<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use PDO;

class InitDatabaseCommand extends Command
{
    protected static $defaultName = 'app:init-database';

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $dbPath = __DIR__ . '/../../var/data/data.db';
        $output->writeln("Chemin de la base de données : $dbPath");

        // Créer le dossier si nécessaire
        if (!is_dir(dirname($dbPath))) {
            mkdir(dirname($dbPath), 0777, true);
            $output->writeln("Dossier créé");
        }

        // Créer la base de données
        try {
            $pdo = new PDO("sqlite:$dbPath");
            
            // Créer la table user
            $pdo->exec('CREATE TABLE IF NOT EXISTS user (
                user_id INTEGER PRIMARY KEY AUTOINCREMENT,
                nom VARCHAR(50) NOT NULL,
                prenom VARCHAR(50) NOT NULL,
                email VARCHAR(100) NOT NULL UNIQUE,
                password VARCHAR(255) NOT NULL,
                role VARCHAR(20) NOT NULL,
                created_at DATETIME NOT NULL,
                updated_at DATETIME NOT NULL
            )');

            // Créer la table produit
            $pdo->exec('CREATE TABLE IF NOT EXISTS produit (
                produit_id INTEGER PRIMARY KEY AUTOINCREMENT,
                nom VARCHAR(100) NOT NULL,
                description TEXT,
                prix_vente_ht TEXT NOT NULL,
                prescription VARCHAR(3) NOT NULL DEFAULT \'non\',
                taux_remboursement INTEGER,
                alerte INTEGER,
                declencher_alerte VARCHAR(3) NOT NULL DEFAULT \'non\',
                is_deleted BOOLEAN DEFAULT 0
            )');

            $output->writeln("Base de données et tables créées avec succès");
            return Command::SUCCESS;

        } catch (\Exception $e) {
            $output->writeln("Erreur : " . $e->getMessage());
            return Command::FAILURE;
        }
    }
} 