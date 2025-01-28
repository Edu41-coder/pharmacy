<?php

namespace App\Controllers;

use App\Models\ACommander;

class ACommanderController {
    private $aCommanderModel;

    public function __construct() {
        $this->aCommanderModel = new ACommander();
    }

    /**
     * Affiche la liste de tous les produits à commander
     */
    public function index() {
        $produits = $this->aCommanderModel->getAllProduits();
        $this->render('a_commander/index', ['produits' => $produits]);
    }

    /**
     * Affiche le formulaire d'ajout d'un produit à commander
     */
    public function create() {
        $this->render('a_commander/create');
    }

    /**
     * Traite la soumission du formulaire d'ajout d'un produit à commander
     */
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $produit_id = $_POST['produit_id'] ?? '';
            $quantite = $_POST['quantite'] ?? '';

            if ($this->aCommanderModel->addProduit($produit_id, $quantite)) {
                $this->redirect('a_commander');
            } else {
                $this->render('a_commander/create', ['error' => 'Erreur lors de l\'ajout du produit à commander']);
            }
        }
    }

    /**
     * Affiche les détails d'un produit à commander
     * @param int $produit_id ID du produit
     */
    public function show($produit_id) {
        $produit = $this->aCommanderModel->getProduitById($produit_id);

        if ($produit) {
            $this->render('a_commander/show', ['produit' => $produit]);
        } else {
            $this->redirect('a_commander');
        }
    }

    /**
     * Affiche le formulaire de modification d'un produit à commander
     * @param int $produit_id ID du produit
     */
    public function edit($produit_id) {
        $produit = $this->aCommanderModel->getProduitById($produit_id);
        if ($produit) {
            $this->render('a_commander/edit', ['produit' => $produit]);
        } else {
            $this->redirect('a_commander');
        }
    }

    /**
     * Traite la soumission du formulaire de modification d'un produit à commander
     * @param int $produit_id ID du produit
     */
    public function update($produit_id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $quantite = $_POST['quantite'] ?? '';

            if ($this->aCommanderModel->updateProduit($produit_id, $quantite)) {
                $this->redirect('a_commander');
            } else {
                $this->render('a_commander/edit', ['produit' => $this->aCommanderModel->getProduitById($produit_id), 'error' => 'Erreur lors de la mise à jour du produit à commander']);
            }
        }
    }

    /**
     * Supprime un produit à commander
     * @param int $produit_id ID du produit
     */
    public function delete($produit_id) {
        if ($this->aCommanderModel->deleteProduit($produit_id)) {
            $this->redirect('a_commander');
        } else {
            $this->render('a_commander/index', ['error' => 'Erreur lors de la suppression du produit à commander']);
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