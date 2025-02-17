<?php
namespace App\Core;

class Container {
    protected $instances = [];
    
    // Enregistre une instance dans le conteneur (si déjà existante, elle n'est pas écrasée)
    public function set($name, $object) {
        if (!isset($this->instances[$name])) {
            $this->instances[$name] = $object;
        }
    }

    // Récupère une instance à partir du conteneur
    public function get($name) {
        if (!isset($this->instances[$name])) {
            $this->instances[$name] = $this->make($name);
        }
        return $this->instances[$name];
    }

    // Crée une nouvelle instance d'une classe et injecte automatiquement les dépendances
    public function make($class) {
        $reflectionClass = new \ReflectionClass($class);
        
        // Si la classe n'a pas de constructeur, créer une instance sans dépendances
        if (!$reflectionClass->getConstructor()) {
            return new $class();
        }

        // Récupérer les paramètres du constructeur
        $constructor = $reflectionClass->getConstructor();
        $parameters = $constructor->getParameters();

        // Pour chaque paramètre, essayer de résoudre sa dépendance à partir du conteneur
        $dependencies = [];
        foreach ($parameters as $parameter) {
            $dependency = $parameter->getType()->getName();

            // Si la dépendance est une classe, l'injecter
            if (class_exists($dependency)) {
                $dependencies[] = $this->get($dependency);
            } else {
                throw new \Exception("La dépendance {$dependency} n'est pas résolue.");
            }
        }

        // Retourne une instance de la classe avec ses dépendances injectées
        return $reflectionClass->newInstanceArgs($dependencies);
    }
}
