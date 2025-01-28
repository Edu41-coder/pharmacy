<?php

namespace App\Controllers;

use App\Models\Fournisseur;

class FournisseurController {
    private $fournisseurModel;

    public function __construct() {
        $this->fournisseurModel = new Fournisseur();
    }

    /**
     * Affiche la liste de tous les fournisseurs
     */
    public function index() {
        $fournisseurs = $this->fournisseurModel->getAllFournisseurs();
        $this->render('fournisseurs/index', ['fournisseurs' => $fournisseurs]);
    }

    /**
     * Affiche le formulaire de création d'un fournisseur
     */
    public function create() {
        $this->render('fournisseurs/create');
    }

    /**
     * Traite la soumission du formulaire de création d'un fournisseur
     */
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nom' => $_POST['nom'] ?? '',
                'adresse' => $_POST['adresse'] ?? '',
                'telephone' => $_POST['telephone'] ?? '',
                'email' => $_POST['email'] ?? '',
                'commentaire' => $_POST['commentaire'] ?? ''
            ];

            if ($this->fournisseurModel->createFournisseur($data)) {
                $this->redirect('fournisseurs');
            } else {
                $this->render('fournisseurs/create', ['error' => 'Erreur lors de la création du fournisseur']);
            }
        }
    }

    /**
     * Affiche les détails d'un fournisseur
     * @param int $id ID du fournisseur
     */
    public function show($id) {
        $fournisseur = $this->fournisseurModel->getFournisseurById($id);
        $produitsFournis = $this->fournisseurModel->getProduitsFournis($id);
        $historiqueCommandes = $this->fournisseurModel->getHistoriqueCommandes($id);

        if ($fournisseur) {
            $this->render('fournisseurs/show', [
                'fournisseur' => $fournisseur,
                'produitsFournis' => $produitsFournis,
                'historiqueCommandes' => $historiqueCommandes
            ]);
        } else {
            $this->redirect('fournisseurs');
        }
    }

    /**
     * Affiche le formulaire de modification d'un fournisseur
     * @param int $id ID du fournisseur
     */
    public function edit($id) {
        $fournisseur = $this->fournisseurModel->getFournisseurById($id);
        if ($fournisseur) {
            $this->render('fournisseurs/edit', ['fournisseur' => $fournisseur]);
        } else {
            $this->redirect('fournisseurs');
        }
    }

    /**
     * Traite la soumission du formulaire de modification d'un fournisseur
     * @param int $id ID du fournisseur
     */
    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nom' => $_POST['nom'] ?? '',
                'adresse' => $_POST['adresse'] ?? '',
                'telephone' => $_POST['telephone'] ?? '',
                'email' => $_POST['email'] ?? ''
            ];

            if ($this->fournisseurModel->updateFournisseur($id, $data)) {
                $this->redirect('fournisseurs');
            } else {
                $this->render('fournisseurs/edit', ['fournisseur' => $data, 'error' => 'Erreur lors de la mise à jour du fournisseur']);
            }
        }
    }

    /**
     * Supprime un fournisseur
     * @param int $id ID du fournisseur
     */
    public function delete($id) {
        if ($this->fournisseurModel->deleteFournisseur($id)) {
            $this->redirect('fournisseurs');
        } else {
            $this->render('fournisseurs/index', ['error' => 'Erreur lors de la suppression du fournisseur']);
        }
    }

    /**
     * Recherche des fournisseurs
     */
    public function search() {
        $search = $_GET['search'] ?? '';
        $fournisseurs = $this->fournisseurModel->searchFournisseurs($search);
        $this->render('fournisseurs/index', ['fournisseurs' => $fournisseurs]);
    }

    /**
     * Ajoute un produit à un fournisseur
     * @param int $fournisseurId ID du fournisseur
     */
    public function ajouterProduit($fournisseurId) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $produitId = $_POST['produit_id'] ?? '';
            $prixAchat = $_POST['prix_achat'] ?? '';

            if ($this->fournisseurModel->ajouterOuMettreAJourProduitFournisseur($fournisseurId, $produitId, $prixAchat)) {
                $this->redirect('fournisseurs/show/' . $fournisseurId);
            } else {
                $this->render('fournisseurs/show/' . $fournisseurId, ['error' => 'Erreur lors de l\'ajout du produit']);
            }
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