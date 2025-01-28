<?php

namespace App\Controllers;

use App\Models\Produit;
use App\Models\Parametre;

class ProduitController
{
    private $produitModel;
    private $parametreModel;

    public function __construct()
    {
        $this->produitModel = new Produit();
        $this->parametreModel = new Parametre();
    }

    public function index() {
        error_log("ProduitController::index() called");
        $produits = $this->produitModel->getAllProduits();
        $tva = $this->parametreModel->getTauxTVA();
        
        foreach ($produits as &$produit) {
            $produit['prix_vente_ttc'] = $produit['prix_vente_ht'] * (1 + $tva / 100);
        }
    
        $role = $_SESSION['role_id']; // Récupérer le rôle de l'utilisateur depuis la session
    
        $this->render('produits/index_produits', ['produits' => $produits, 'role' => $role]);
    }

    public function create()
    {
        error_log("ProduitController::create() called");
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            error_log("ProduitController::create() - POST request");
            $result = $this->produitModel->createProduit(
                $_POST['nom'],
                $_POST['description'],
                $_POST['prix_vente_ht'],
                $_POST['prescription'],
                $_POST['taux_remboursement'],
                $_POST['alerte'],
                $_POST['declencher_alerte']
            );

            if ($result) {
                error_log("ProduitController::create() - Product created successfully");
                $this->redirect('index'); // Rediriger vers l'action index après la création
            } else {
                error_log("ProduitController::create() - Error creating product");
                $this->render('produits/create_produit', ['error' => "Erreur lors de la création du produit."]);
            }
        } else {
            error_log("ProduitController::create() - GET request");
            $this->render('produits/create_produit');
        }
    }

    public function read($id)
    {
        error_log("ProduitController::read() called with id: $id");
        $produit = $this->produitModel->getProduitById($id);
        $tva = $this->parametreModel->getTauxTVA();
        $produit['prix_vente_ttc'] = $produit['prix_vente_ht'] * (1 + $tva / 100);
        $fournisseurs = $this->produitModel->getFournisseursPrix($id);

        $this->render('produits/details', ['produit' => $produit, 'fournisseurs' => $fournisseurs]);
    }

    public function update($id)
    {
        error_log("ProduitController::update() called with id: $id");
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            error_log("ProduitController::update() - POST request");
            $result = $this->produitModel->updateProduit(
                $id,
                $_POST['nom'],
                $_POST['description'],
                $_POST['prix_vente_ht'],
                $_POST['prescription'],
                $_POST['taux_remboursement'],
                $_POST['alerte'],
                $_POST['declencher_alerte']
            );

            if ($result) {
                error_log("ProduitController::update() - Product updated successfully");
                $this->redirect('show', ['id' => $id]);
            } else {
                error_log("ProduitController::update() - Error updating product");
                $this->render('produits/edit', ['error' => "Erreur lors de la mise à jour du produit.", 'id' => $id]);
            }
        } else {
            error_log("ProduitController::update() - GET request");
            $produit = $this->produitModel->getProduitById($id);
            $this->render('produits/edit', ['produit' => $produit]);
        }
    }

    public function delete($id)
    {
        error_log("ProduitController::delete() called with id: $id");
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            error_log("ProduitController::delete() - POST request");
            if ($this->produitModel->deleteProduit($id)) {
                error_log("ProduitController::delete() - Product deleted successfully");
                $this->redirect('index'); // Rediriger vers l'action index après la suppression
            } else {
                error_log("ProduitController::delete() - Error deleting product");
                $this->render('produits/delete', ['error' => "Erreur lors de la suppression du produit.", 'id' => $id]);
            }
        } else {
            error_log("ProduitController::delete() - GET request");
            $produit = $this->produitModel->getProduitById($id);
            $this->render('produits/delete', ['produit' => $produit]);
        }
    }

    private function render($view, $data = [])
    {
        error_log("ProduitController::render() called with view: $view");
        extract($data);
        require_once __DIR__ . "/../views/$view.php";
    }

    private function redirect($action, $params = [])
    {
        error_log("ProduitController::redirect() called with action: $action");
        $url = "index.php?action=$action";
        foreach ($params as $key => $value) {
            $url .= "&$key=$value";
        }
        error_log("ProduitController::redirect() - Redirecting to: $url");
        header("Location: $url");
        exit();
    }
}