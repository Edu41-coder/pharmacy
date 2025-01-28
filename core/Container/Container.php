<?php

namespace Core\Container;

use Closure;
use ReflectionClass;
use Core\Container\ContainerException;

class Container implements ContainerInterface
{
    /**
     * Les services enregistrés
     * @var array
     */
    private array $services = [];

    /**
     * Les instances déjà créées (singleton)
     * @var array
     */
    private array $instances = [];

    /**
     * Enregistre un service
     */
    public function set(string $id, $concrete): void
    {
        if (!$concrete instanceof Closure) {
            $concrete = function () use ($concrete) {
                return $concrete;
            };
        }
        
        $this->services[$id] = $concrete;
    }

    /**
     * Récupère un service
     */
    public function get(string $id)
    {
        // Vérifie si le service existe
        if (!$this->has($id)) {
            if (class_exists($id)) {
                return $this->resolve($id);  // Auto-résolution
            }
            throw new ContainerException("Service '$id' not found");
        }

        // Singleton : retourne l'instance existante
        if (isset($this->instances[$id])) {
            return $this->instances[$id];
        }

        // Crée l'instance via la closure définie dans services.php
        $concrete = $this->services[$id];
        $this->instances[$id] = $concrete($this);

        return $this->instances[$id];
    }

    /**
     * Vérifie si un service existe
     */
    public function has(string $id): bool
    {
        return isset($this->services[$id]);
    }

    /**
     * Résout automatiquement les dépendances d'une classe
     */
    private function resolve(string $id)
    {
        // Analyse la classe avec Reflection
        $reflector = new ReflectionClass($id);
        
        if (!$reflector->isInstantiable()) {
            throw new ContainerException("Class '$id' is not instantiable");
        }

        // Récupère le constructeur
        $constructor = $reflector->getConstructor();
        
        if (is_null($constructor)) {
            return new $id();
        }

        // Récupère les paramètres du constructeur
        $parameters = $constructor->getParameters();
        $dependencies = $this->resolveDependencies($parameters);

        // Crée une nouvelle instance avec les dépendances
        return $reflector->newInstanceArgs($dependencies);
    }

    /**
     * Résout les dépendances pour les paramètres donnés
     */
    private function resolveDependencies(array $parameters): array
    {
        $dependencies = [];

        foreach ($parameters as $parameter) {
            $type = $parameter->getType();
            
            if (!$type) {
                if ($parameter->isDefaultValueAvailable()) {
                    $dependencies[] = $parameter->getDefaultValue();
                    continue;
                }
                throw new ContainerException(
                    "Cannot resolve parameter '{$parameter->getName()}'"
                );
            }

            $typeName = $type->getName();
            
            if ($type->isBuiltin()) {
                if ($parameter->isDefaultValueAvailable()) {
                    $dependencies[] = $parameter->getDefaultValue();
                    continue;
                }
                throw new ContainerException(
                    "Cannot resolve parameter '{$parameter->getName()}' of type '$typeName'"
                );
            }

            $dependencies[] = $this->get($typeName);
        }

        return $dependencies;
    }
} 