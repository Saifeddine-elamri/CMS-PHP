<?php
namespace App\Core;

use ReflectionClass;
use ReflectionException;

class Container {
    protected $instances = [];
    protected $bindings = [];

    // Enregistre une classe ou une interface avec son implémentation
    public function bind($abstract, $concrete) {
        $this->bindings[$abstract] = $concrete;
    }

    // Enregistre une instance dans le conteneur
    public function set($name, $object) {
        if (!isset($this->instances[$name])) {
            $this->instances[$name] = $object;
        }
    }

    // Récupère une instance à partir du conteneur
    public function get($name) {
        // Vérifie si l'instance existe déjà
        if (isset($this->instances[$name])) {
            return $this->instances[$name];
        }

        // Vérifie s'il y a une implémentation enregistrée pour l'interface ou la classe abstraite
        if (isset($this->bindings[$name])) {
            $name = $this->bindings[$name];
        }

        // Utilise la méthode make pour créer l'instance
        return $this->make($name);
    }

    // Crée une nouvelle instance d'une classe et injecte automatiquement les dépendances
    public function make($class) {
        try {
            $reflectionClass = new ReflectionClass($class);

            // Si la classe n'a pas de constructeur, on instancie simplement
            if (!$constructor = $reflectionClass->getConstructor()) {
                return new $class();
            }

            // Récupérer les paramètres du constructeur
            $parameters = $constructor->getParameters();
            $dependencies = [];

            foreach ($parameters as $parameter) {
                $dependency = $parameter->getType()?->getName();

                // Si le type est une classe, on la résout via le conteneur
                if ($dependency && class_exists($dependency)) {
                    $dependencies[] = $this->get($dependency);
                } elseif ($parameter->isDefaultValueAvailable()) {
                    $dependencies[] = $parameter->getDefaultValue();
                } else {
                    throw new \Exception("Impossible de résoudre la dépendance {$parameter->getName()} dans la classe $class.");
                }
            }

            // On crée l'instance en passant les dépendances au constructeur
            return $reflectionClass->newInstanceArgs($dependencies);
        } catch (ReflectionException $e) {
            throw new \Exception("Erreur lors de la création de l'instance de $class: " . $e->getMessage());
        }
    }
}
