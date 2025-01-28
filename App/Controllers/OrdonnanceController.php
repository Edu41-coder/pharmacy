<?php

namespace App\Controllers;

use App\Models\Ordonnance;

class OrdonnanceController {
    private $ordonnanceModel;

    public function __construct() {
        $this->ordonnanceModel = new Ordonnance();
    }

    /**
     * Affiche la liste de toutes les ordonnances
     */
    public function index() {
        $ordonnances = $this->ordonnanceModel->getAllOrdonnances();
        $this->render('ordonnances/index', ['ordonnances' => $ordonnances]);
    }

    /**
     * Affiche le formulaire de création d'une ordonnance
     */
    public function create() {
        $this->render('ordonnances/create');
    }

    /**
     * Traite la soumission du formulaire de création d'une ordonnance
     */
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $numero_ordonnance = $_POST['numero_ordonnance'] ?? '';
            $numero_dordre = $_POST['numero_dordre'] ?? '';
            $image_path = null;

            if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
                $image_path = 'uploads/' . basename($_FILES['image']['name']);
                move_uploaded_file($_FILES['image']['tmp_name'], $image_path);
            }

            if ($this->ordonnanceModel->createOrdonnance($numero_ordonnance, $numero_dordre, $image_path)) {
                $this->redirect('ordonnances');
            } else {
                $this->render('ordonnances/create', ['error' => 'Erreur lors de la création de l\'ordonnance']);
            }
        }
    }

    /**
     * Affiche les détails d'une ordonnance
     * @param int $ordonnance_id ID de l'ordonnance
     */
    public function show($ordonnance_id) {
        $ordonnance = $this->ordonnanceModel->getOrdonnanceById($ordonnance_id);
        $images = $this->ordonnanceModel->getImagesByOrdonnance($ordonnance_id);
        $produits = $this->ordonnanceModel->getProduitsByOrdonnance($ordonnance_id);

        if ($ordonnance) {
            $this->render('ordonnances/show', ['ordonnance' => $ordonnance, 'images' => $images, 'produits' => $produits]);
        } else {
            $this->redirect('ordonnances');
        }
    }

    /**
     * Affiche le formulaire de modification d'une ordonnance
     * @param int $ordonnance_id ID de l'ordonnance
     */
    public function edit($ordonnance_id) {
        $ordonnance = $this->ordonnanceModel->getOrdonnanceById($ordonnance_id);
        if ($ordonnance) {
            $this->render('ordonnances/edit', ['ordonnance' => $ordonnance]);
        } else {
            $this->redirect('ordonnances');
        }
    }

    /**
     * Traite la soumission du formulaire de modification d'une ordonnance
     * @param int $ordonnance_id ID de l'ordonnance
     */
    public function update($ordonnance_id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $numero_ordonnance = $_POST['numero_ordonnance'] ?? '';
            $numero_dordre = $_POST['numero_dordre'] ?? '';
            $image_path = null;

            if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
                $image_path = 'uploads/' . basename($_FILES['image']['name']);
                move_uploaded_file($_FILES['image']['tmp_name'], $image_path);
            }

            if ($this->ordonnanceModel->updateOrdonnance($ordonnance_id, $numero_ordonnance, $numero_dordre, $image_path)) {
                $this->redirect('ordonnances');
            } else {
                $this->render('ordonnances/edit', ['ordonnance' => $this->ordonnanceModel->getOrdonnanceById($ordonnance_id), 'error' => 'Erreur lors de la mise à jour de l\'ordonnance']);
            }
        }
    }

    /**
     * Supprime une ordonnance et son image associée
     * @param int $ordonnance_id ID de l'ordonnance
     */
    public function delete($ordonnance_id) {
        $ordonnance = $this->ordonnanceModel->getOrdonnanceById($ordonnance_id);
        if ($ordonnance) {
            // Supprimer l'image associée si elle existe
            if ($ordonnance['image_path'] && file_exists($ordonnance['image_path'])) {
                unlink($ordonnance['image_path']);
            }
            // Supprimer l'ordonnance
            if ($this->ordonnanceModel->deleteOrdonnance($ordonnance_id)) {
                $this->redirect('ordonnances');
            } else {
                $this->render('ordonnances/index', ['error' => 'Erreur lors de la suppression de l\'ordonnance']);
            }
        } else {
            $this->redirect('ordonnances');
        }
    }

    /**
     * Supprime une image d'ordonnance
     * @param int $image_id ID de l'image
     */
    public function deleteImage($image_id) {
        $image = $this->ordonnanceModel->getImageOrdonnanceById($image_id);
        if ($image) {
            // Supprimer l'image du système de fichiers
            if (file_exists($image['image_path'])) {
                unlink($image['image_path']);
            }
            // Supprimer l'image de la base de données
            if ($this->ordonnanceModel->deleteImageFromOrdonnance($image_id)) {
                $this->redirect('ordonnances');
            } else {
                $this->render('ordonnances/index', ['error' => 'Erreur lors de la suppression de l\'image']);
            }
        } else {
            $this->redirect('ordonnances');
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