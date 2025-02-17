<?php
namespace App\Services;

class FileUploadService {
    // Dossier de destination pour les fichiers téléchargés
    protected $uploadDir = 'public/uploads/';

    /**
     * Gérer l'upload d'un fichier
     * @param array $file Le fichier provenant de $_FILES
     * @return string|null Le chemin du fichier ou null en cas d'échec
     */
    public function upload($file) {
        // Vérifier les erreurs d'upload
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return null; // Pas de fichier téléchargé ou erreur
        }

        // Vérifier le type du fichier
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'application/pdf'];
        if (!in_array($file['type'], $allowedTypes)) {
            return null; // Format de fichier non autorisé
        }

        // Générer un nom unique pour le fichier
        $filePath = $this->uploadDir . uniqid() . '-' . basename($file['name']);

        // Déplacer le fichier téléchargé
        if (move_uploaded_file($file['tmp_name'], $filePath)) {
            return $filePath; // Retourner le chemin du fichier téléchargé
        }

        return null; // Si le téléchargement échoue, retourner null
    }
}
