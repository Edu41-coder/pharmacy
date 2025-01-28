<?php

namespace App\Controllers;

use App\Models\Inventaire;
use App\Models\Produit;

class InventaireController
{
    private $inventaireModel;
    private $produitModel;

    public function __construct()
    {
        $this->inventaireModel = new Inventaire();
        $this->produitModel = new Produit();
    }

    public function index()
    {
        $inventaire = $this->inventaireModel->getAll();
        $this->render('inventaire/index', ['inventaire' => $inventaire, 'produitModel' => $this->produitModel]);
    }
    public function checkAlerte($produit_id)
    {
        $alerte = $this->inventaireModel->alerte($produit_id);
        if ($alerte) {
            echo "Alerte: Le produit avec ID $produit_id a un stock inférieur au seuil d'alerte.";
        } else {
            echo "Pas d'alerte pour le produit avec ID $produit_id.";
        }
    }
    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $result = $this->inventaireModel->create(
                $_POST['produit_id'],
                $_POST['stock']
            );

            if ($result) {
                $this->redirect('inventaire');
            } else {
                $this->render('inventaire/create', ['error' => "Erreur lors de la création de l'inventaire."]);
            }
        } else {
            $this->render('inventaire/create');
        }
    }

    public function read($produit_id)
    {
        $inventaire = $this->inventaireModel->getById($produit_id);
        $produit = $this->produitModel->getById($produit_id);
        $this->render('inventaire/details', ['inventaire' => $inventaire, 'produit' => $produit]);
    }

    public function update($produit_id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $result = $this->inventaireModel->update(
                $produit_id,
                $_POST['stock']
            );

            if ($result) {
                $this->redirect('inventaire_details', ['produit_id' => $produit_id]);
            } else {
                $this->render('inventaire/edit', ['error' => "Erreur lors de la mise à jour de l'inventaire.", 'produit_id' => $produit_id]);
            }
        } else {
            $inventaire = $this->inventaireModel->getById($produit_id);
            $this->render('inventaire/edit', ['inventaire' => $inventaire]);
        }
    }

    public function delete($produit_id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($this->inventaireModel->delete($produit_id)) {
                $this->redirect('inventaire');
            } else {
                $this->render('inventaire/delete', ['error' => "Erreur lors de la suppression de l'inventaire.", 'produit_id' => $produit_id]);
            }
        } else {
            $inventaire = $this->inventaireModel->getById($produit_id);
            $this->render('inventaire/delete', ['inventaire' => $inventaire]);
        }
    }

    private function render($view, $data = [])
    {
        extract($data);
        require_once("../app/views/$view.php");
    }

    private function redirect($action, $params = [])
    {
        $url = "index.php?action=$action";
        foreach ($params as $key => $value) {
            $url .= "&$key=$value";
        }
        header("Location: $url");
        exit();
    }
}
