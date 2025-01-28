<?php

namespace App\Controllers;

use App\Models\Client;

class ClientController {
    private $clientModel;

    public function __construct() {
        $this->clientModel = new Client();
    }

    /**
     * Affiche la liste de tous les clients
     */
    public function index() {
        $clients = $this->clientModel->getAllClients();
        $this->render('clients/index', ['clients' => $clients]);
    }

    /**
     * Affiche le formulaire de création d'un client
     */
    public function create() {
        $this->render('clients/create');
    }

    /**
     * Traite la soumission du formulaire de création d'un client
     */
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nom' => $_POST['nom'] ?? '',
                'prenom' => $_POST['prenom'] ?? '',
                'email' => $_POST['email'] ?? '',
                'telephone' => $_POST['telephone'] ?? '',
                'adresse' => $_POST['adresse'] ?? ''
            ];

            if ($this->clientModel->createClient($data)) {
                $this->redirect('clients');
            } else {
                $this->render('clients/create', ['error' => 'Erreur lors de la création du client']);
            }
        }
    }

    /**
     * Affiche les détails d'un client
     * @param int $id ID du client
     */
    public function show($id) {
        $client = $this->clientModel->getClientById($id);
        $historiqueAchats = $this->clientModel->getClientPurchaseHistory($id);

        if ($client) {
            $this->render('clients/show', [
                'client' => $client,
                'historiqueAchats' => $historiqueAchats
            ]);
        } else {
            $this->redirect('clients');
        }
    }

    /**
     * Affiche le formulaire de modification d'un client
     * @param int $id ID du client
     */
    public function edit($id) {
        $client = $this->clientModel->getClientById($id);
        if ($client) {
            $this->render('clients/edit', ['client' => $client]);
        } else {
            $this->redirect('clients');
        }
    }

    /**
     * Traite la soumission du formulaire de modification d'un client
     * @param int $id ID du client
     */
    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nom' => $_POST['nom'] ?? '',
                'prenom' => $_POST['prenom'] ?? '',
                'email' => $_POST['email'] ?? '',
                'telephone' => $_POST['telephone'] ?? '',
                'adresse' => $_POST['adresse'] ?? ''
            ];

            if ($this->clientModel->updateClient($id, $data)) {
                $this->redirect('clients');
            } else {
                $this->render('clients/edit', ['client' => $data, 'error' => 'Erreur lors de la mise à jour du client']);
            }
        }
    }

    /**
     * Supprime un client
     * @param int $id ID du client
     */
    public function delete($id) {
        if ($this->clientModel->deleteClient($id)) {
            $this->redirect('clients');
        } else {
            $this->render('clients/index', ['error' => 'Erreur lors de la suppression du client']);
        }
    }

    /**
     * Recherche des clients
     */
    public function search() {
        $search = $_GET['search'] ?? '';
        $clients = $this->clientModel->searchClients($search);
        $this->render('clients/index', ['clients' => $clients]);
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