<?php

namespace App\Controllers;

use App\Models\Vente;

class VenteController {
    private $venteModel;

    public function __construct() {
        $this->venteModel = new Vente();
    }

    /**
     * Affiche la liste de toutes les ventes
     */
    public function index() {
        $ventes = $this->venteModel->getAllVentes();
        $this->render('ventes/index', ['ventes' => $ventes]);
    }

    /**
     * Affiche le formulaire de création d'une vente
     */
    public function create() {
        $this->render('ventes/create');
    }

    /**
     * Traite la soumission du formulaire de création d'une vente
     */
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'client_id' => $_POST['client_id'] ?? null,
                'mode_encaissement' => $_POST['mode_encaissement'] ?? '',
                'numero_cheque' => $_POST['numero_cheque'] ?? null,
                'commentaire' => $_POST['commentaire'] ?? null
            ];

            if ($this->venteModel->createVente($data)) {
                $this->redirect('ventes');
            } else {
                $this->render('ventes/create', ['error' => 'Erreur lors de la création de la vente']);
            }
        }
    }

    /**
     * Affiche les détails d'une vente
     * @param int $id ID de la vente
     */
    public function show($id) {
        $vente = $this->venteModel->getVenteById($id);

        if ($vente) {
            $this->render('ventes/show', ['vente' => $vente]);
        } else {
            $this->redirect('ventes');
        }
    }

    /**
     * Affiche le formulaire de modification d'une vente
     * @param int $id ID de la vente
     */
    public function edit($id) {
        $vente = $this->venteModel->getVenteById($id);
        if ($vente) {
            $this->render('ventes/edit', ['vente' => $vente]);
        } else {
            $this->redirect('ventes');
        }
    }

    /**
     * Traite la soumission du formulaire de modification d'une vente
     * @param int $id ID de la vente
     */
    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'client_id' => $_POST['client_id'] ?? null,
                'mode_encaissement' => $_POST['mode_encaissement'] ?? '',
                'numero_cheque' => $_POST['numero_cheque'] ?? null,
                'commentaire' => $_POST['commentaire'] ?? null
            ];

            if ($this->venteModel->updateVente($id, $data)) {
                $this->redirect('ventes');
            } else {
                $this->render('ventes/edit', ['vente' => $data, 'error' => 'Erreur lors de la mise à jour de la vente']);
            }
        }
    }

    /**
     * Supprime une vente
     * @param int $id ID de la vente
     */
    public function delete($id) {
        if ($this->venteModel->deleteVente($id)) {
            $this->redirect('ventes');
        } else {
            $this->render('ventes/index', ['error' => 'Erreur lors de la suppression de la vente']);
        }
    }

    /**
     * Recherche des ventes
     */
    public function search() {
        $search = $_GET['search'] ?? '';
        $criteria = [
            'client_id' => $search,
            'mode_encaissement' => $search,
            'numero_cheque' => $search,
            'commentaire' => $search
        ];
        $ventes = $this->venteModel->searchVentes($criteria);
        $this->render('ventes/index', ['ventes' => $ventes]);
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