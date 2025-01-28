<?php

namespace App\Controllers;

use App\Models\Impaye;

class ImpayeController {
    private $impayeModel;

    public function __construct() {
        $this->impayeModel = new Impaye();
    }

    /**
     * Affiche la liste de tous les impayés
     */
    public function index() {
        $impayes = $this->impayeModel->getAllImpaye();
        $this->render('impayes/index', ['impayes' => $impayes]);
    }

    /**
     * Affiche le formulaire de création d'un impayé
     */
    public function create() {
        $this->render('impayes/create');
    }

    /**
     * Traite la soumission du formulaire de création d'un impayé
     */
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $numero_cheque = $_POST['numero_cheque'] ?? '';
            $client_id = $_POST['client_id'] ?? '';

            if ($this->impayeModel->createImpaye($numero_cheque, $client_id)) {
                $this->redirect('impayes');
            } else {
                $this->render('impayes/create', ['error' => 'Erreur lors de la création de l\'impayé']);
            }
        }
    }

    /**
     * Affiche les détails d'un impayé
     * @param int $impaye_id ID de l'impayé
     */
    public function show($impaye_id) {
        $impaye = $this->impayeModel->getImpayeById($impaye_id);

        if ($impaye) {
            $this->render('impayes/show', ['impaye' => $impaye]);
        } else {
            $this->redirect('impayes');
        }
    }

    /**
     * Affiche le formulaire de modification d'un impayé
     * @param int $impaye_id ID de l'impayé
     */
    public function edit($impaye_id) {
        $impaye = $this->impayeModel->getImpayeById($impaye_id);
        if ($impaye) {
            $this->render('impayes/edit', ['impaye' => $impaye]);
        } else {
            $this->redirect('impayes');
        }
    }

    /**
     * Traite la soumission du formulaire de modification d'un impayé
     * @param int $impaye_id ID de l'impayé
     */
    public function update($impaye_id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $numero_cheque = $_POST['numero_cheque'] ?? '';
            $client_id = $_POST['client_id'] ?? '';

            if ($this->impayeModel->updateImpaye($impaye_id, $numero_cheque, $client_id)) {
                $this->redirect('impayes');
            } else {
                $this->render('impayes/edit', ['impaye' => $this->impayeModel->getImpayeById($impaye_id), 'error' => 'Erreur lors de la mise à jour de l\'impayé']);
            }
        }
    }

    /**
     * Supprime un impayé
     * @param int $impaye_id ID de l'impayé
     */
    public function delete($impaye_id) {
        if ($this->impayeModel->deleteImpaye($impaye_id)) {
            $this->redirect('impayes');
        } else {
            $this->render('impayes/index', ['error' => 'Erreur lors de la suppression de l\'impayé']);
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