<?php

namespace App\Controllers;

use App\Models\Cheque;

class ChequeController {
    private $chequeModel;

    public function __construct() {
        $this->chequeModel = new Cheque();
    }

    /**
     * Affiche la liste de tous les chèques
     */
    public function index() {
        $cheques = $this->chequeModel->getAllCheques();
        $this->render('cheques/index', ['cheques' => $cheques]);
    }

    /**
     * Affiche le formulaire de création d'un chèque
     */
    public function create() {
        $this->render('cheques/create');
    }

    /**
     * Traite la soumission du formulaire de création d'un chèque
     */
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $numero_cheque = $_POST['numero_cheque'] ?? '';
            $client_id = $_POST['client_id'] ?? '';
            $etat = $_POST['etat'] ?? 'en_attente';

            if ($this->chequeModel->createCheque($numero_cheque, $client_id, $etat)) {
                $this->redirect('cheques');
            } else {
                $this->render('cheques/create', ['error' => 'Erreur lors de la création du chèque']);
            }
        }
    }

    /**
     * Affiche les détails d'un chèque
     * @param int $cheque_id ID du chèque
     */
    public function show($cheque_id) {
        $cheque = $this->chequeModel->getChequeById($cheque_id);

        if ($cheque) {
            $this->render('cheques/show', ['cheque' => $cheque]);
        } else {
            $this->redirect('cheques');
        }
    }

    /**
     * Affiche le formulaire de modification d'un chèque
     * @param int $cheque_id ID du chèque
     */
    public function edit($cheque_id) {
        $cheque = $this->chequeModel->getChequeById($cheque_id);
        if ($cheque) {
            $this->render('cheques/edit', ['cheque' => $cheque]);
        } else {
            $this->redirect('cheques');
        }
    }

    /**
     * Traite la soumission du formulaire de modification d'un chèque
     * @param int $cheque_id ID du chèque
     */
    public function update($cheque_id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $numero_cheque = $_POST['numero_cheque'] ?? '';
            $client_id = $_POST['client_id'] ?? '';
            $etat = $_POST['etat'] ?? 'en_attente';

            // Récupérer les informations du chèque avant de les utiliser
            $cheque = $this->chequeModel->getChequeById($cheque_id);

            if ($this->chequeModel->updateCheque($cheque_id, $numero_cheque, $client_id, $etat)) {
                $this->redirect('cheques');
            } else {
                $this->render('cheques/edit', ['cheque' => $cheque, 'error' => 'Erreur lors de la mise à jour du chèque']);
            }
        }
    }

    /**
     * Supprime un chèque
     * @param int $cheque_id ID du chèque
     */
    public function delete($cheque_id) {
        if ($this->chequeModel->deleteCheque($cheque_id)) {
            $this->redirect('cheques');
        } else {
            $this->render('cheques/index', ['error' => 'Erreur lors de la suppression du chèque']);
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