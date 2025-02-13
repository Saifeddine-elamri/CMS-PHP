<?php
// autoload.php

spl_autoload_register(function ($className) {
    // Convertit le namespace en chemin de fichier
    $className = str_replace('\\', DIRECTORY_SEPARATOR, $className);

    // Chemin de base de l'application (remonte d'un niveau pour aller dans SGC)
    $baseDir = __DIR__ . '/../';  // On remonte d'un niveau pour atteindre 'SGC'

    // Chemin complet du fichier
    $file = $baseDir . DIRECTORY_SEPARATOR . $className . '.php';

    // Vérifie si le fichier existe et le charge
    if (file_exists($file)) {
        require $file;
    } else {
        throw new Exception("La classe $className n'a pas pu être chargée. Fichier non trouvé : $file");
    }
});

// Exemple : Charger la configuration de la base de données
require __DIR__ . '/../config/database.php';
