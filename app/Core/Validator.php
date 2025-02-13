<?php

namespace App\Core;

class Validator {
    private $data;           // Données à valider
    private $rules = [];     // Règles de validation
    private $errors = [];    // Messages d'erreur

    // Constructeur : Initialise les données à valider
    public function __construct($data) {
        $this->data = $data;
    }

    // Ajouter une règle de validation pour un champ spécifique
    public function addRule($field, $rule, $options = [], $message = null) {
        $this->rules[$field][] = [
            'rule' => $rule,
            'options' => $options,
            'message' => $message
        ];
        return $this;
    }

    // Exécuter toutes les validations
    public function validate() {
        foreach ($this->rules as $field => $rules) {
            foreach ($rules as $rule) {
                $ruleName = $rule['rule'];
                $options = $rule['options'];
                $message = $rule['message'] ?? null;

                // Appel dynamique des méthodes de validation
                $methodName = "validate" . ucfirst($ruleName);
                if (method_exists($this, $methodName)) {
                    $this->$methodName($field, $options, $message);
                }
            }
        }
        return empty($this->errors);
    }

    // Obtenir les erreurs après validation
    public function getErrors() {
        return $this->errors;
    }

    // Vérifie si un champ spécifique a une erreur
    public function hasError($field) {
        return isset($this->errors[$field]);
    }

    // 1. Règle : Champ requis
    private function validateRequired($field, $options, $message) {
        if (empty(trim($this->data[$field] ?? ''))) {
            $this->errors[$field] = $message ?? "Le champ $field est requis.";
        }
    }

    // 2. Règle : Longueur minimale
    private function validateMin($field, $options, $message) {
        $min = $options['min'] ?? 0;
        if (strlen($this->data[$field] ?? '') < $min) {
            $this->errors[$field] = $message ?? "Le champ $field doit avoir au moins $min caractères.";
        }
    }

    // 3. Règle : Email valide
    private function validateEmail($field, $options, $message) {
        if (!filter_var($this->data[$field] ?? '', FILTER_VALIDATE_EMAIL)) {
            $this->errors[$field] = $message ?? "Le champ $field doit être un email valide.";
        }
    }

    // 4. Règle : Type de fichier
    private function validateFileType($field, $options, $message) {
        if (!empty($_FILES[$field]['name'])) {
            $allowedTypes = $options['types'] ?? [];
            $fileType = $_FILES[$field]['type'];

            if (!in_array($fileType, $allowedTypes)) {
                $this->errors[$field] = $message ?? "Le fichier doit être de type : " . implode(", ", $allowedTypes);
            }
        }
    }

    // 5. Règle : Taille maximale du fichier
    private function validateFileSize($field, $options, $message) {
        if (!empty($_FILES[$field]['name'])) {
            $maxSize = $options['maxSize'] ?? 2 * 1024 * 1024;
            $fileSize = $_FILES[$field]['size'];

            if ($fileSize > $maxSize) {
                $this->errors[$field] = $message ?? "Le fichier ne doit pas dépasser " . ($maxSize / (1024 * 1024)) . " Mo.";
            }
        }
    }
}
