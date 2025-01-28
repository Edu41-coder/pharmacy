<?php

namespace App\Controllers;

use App\Models\Commande;

class CommandeController {
    private $commandeModel;

    public function __construct() {
        $this->commandeModel = new Commande();
    }

    /**
     * Affiche la liste de toutes les commandes
     */
    public function index() {
        $commandes = $this->commandeModel->getAllCommandes();
        $this->render('commandes/index', ['commandes' => $commandes]);
    }

    /**
     * Affiche le formulaire de création d'une commande
     */
    public function create() {
        $this->render('commandes/create');
    }

    /**
     * Traite la soumission du formulaire de création d'une commande
     */
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $fournisseur_id = $_POST['fournisseur_id'] ?? '';

            if ($this->commandeModel->createCommande($fournisseur_id)) {
                $this->redirect('commandes');
            } else {
                $this->render('commandes/create', ['error' => 'Erreur lors de la création de la commande']);
            }
        }
    }

    /**
     * Affiche les détails d'une commande
     * @param int $commande_id ID de la commande
     */
    public function show($commande_id) {
        $commande = $this->commandeModel->getCommandeById($commande_id);

        if ($commande) {
            $produits = $this->commandeModel->getProduitsByCommande($commande_id);
            $this->render('commandes/show', ['commande' => $commande, 'produits' => $produits]);
        } else {
            $this->redirect('commandes');
        }
    }

    /**
     * Affiche le formulaire de modification d'une commande
     * @param int $commande_id ID de la commande
     */
    public function edit($commande_id) {
        $commande = $this->commandeModel->getCommandeById($commande_id);
        if ($commande) {
            $this->render('commandes/edit', ['commande' => $commande]);
        } else {
            $this->redirect('commandes');
        }
    }

    /**
     * Traite la soumission du formulaire de modification d'une commande
     * @param int $commande_id ID de la commande
     */
    public function update($commande_id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $fournisseur_id = $_POST['fournisseur_id'] ?? '';

            if ($this->commandeModel->updateCommande($commande_id, $fournisseur_id)) {
                $this->redirect('commandes');
            } else {
                $this->render('commandes/edit', ['commande' => $this->commandeModel->getCommandeById($commande_id), 'error' => 'Erreur lors de la mise à jour de la commande']);
            }
        }
    }

    /**
     * Supprime une commande
     * @param int $commande_id ID de la commande
     */
    public function delete($commande_id) {
        if ($this->commandeModel->deleteCommande($commande_id)) {
            $this->redirect('commandes');
        } else {
            $this->render('commandes/index', ['error' => 'Erreur lors de la suppression de la commande']);
        }
    }

    /**
     * Affiche une vue
     * @param string $view Nom de la vue
     * @param array $data Données à passer à la vue
     */
    private function render($view, $data = []) {
        extract($data);
        require_once("../app/views/$view.php");
    }

    /**
     * Redirige vers une autre action
     * @param string $action Nom de l'action
     */
    private function redirect($action) {
        header("Location: index.php?action=$action");
        exit();
    }
}