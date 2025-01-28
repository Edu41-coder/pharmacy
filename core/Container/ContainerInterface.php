<?php

namespace Core\Container;

interface ContainerInterface
{
    /**
     * Récupère une instance d'un service
     * 
     * @param string $id Identifiant du service
     * @return mixed Instance du service
     * @throws ContainerException Si le service n'existe pas
     */
    public function get(string $id);

    /**
     * Vérifie si un service existe
     * 
     * @param string $id Identifiant du service
     * @return bool
     */
    public function has(string $id): bool;

    /**
     * Enregistre un service dans le conteneur
     * 
     * @param string $id Identifiant du service
     * @param mixed $concrete Instance ou closure du service
     * @return void
     */
    public function set(string $id, $concrete): void;
} 