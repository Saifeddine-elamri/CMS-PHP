<?php
// app/Repositories/Repository.php

class Repository {
    // Méthode pour sauvegarder un modèle
    public function save($model) {
        if (!$model instanceof Model) {
            throw new Exception('Le modèle doit être une instance de la classe Model');
        }
        // Logique pour sauvegarder l'instance du modèle
        // Par exemple, on pourrait utiliser une base de données ou une autre forme de stockage
        echo 'Enregistrement de ' . get_class($model) . " : Sauvegarde réussie.\n";
    }

    // Méthode pour trouver un modèle par son ID
    public function find($id) {
        // Logique pour trouver un modèle par son ID
        // Ici, on peut simuler une recherche ou se connecter à une base de données
        echo 'Recherche du modèle avec ID : ' . $id . "\n";
        
        // Retourner un modèle fictif pour la démonstration
        return new Model();  // Retourne une instance du modèle de base
    }

    // Méthode pour supprimer un modèle
    public function delete($model) {
        if (!$model instanceof Model) {
            throw new Exception('Le modèle doit être une instance de la classe Model');
        }
        // Logique pour supprimer le modèle, par exemple dans la base de données
        echo 'Suppression du modèle : ' . get_class($model) . "\n";
    }

    // Méthode pour récupérer tous les modèles
    public function findAll() {
        // Logique pour récupérer tous les modèles
        echo 'Récupération de tous les modèles.' . "\n";
        
        // Retourner un tableau vide pour la démonstration
        return [];
    }
}
?>
