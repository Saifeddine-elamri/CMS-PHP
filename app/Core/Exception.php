<?php

namespace App\Core;

class Exception extends \Exception
{
    // Propriété pour la personnalisation du message d'erreur
    protected $message = 'Une erreur est survenue dans l\'application.';
    
    // Constructeur qui permet de personnaliser le message et le code de l'exception
    public function __construct($message = null, $code = 0)
    {
        if ($message) {
            $this->message = $message;
        }
        
        parent::__construct($this->message, $code);
    }

    // Optionnel : méthode pour personnaliser l'affichage des erreurs
    public function errorMessage()
    {
        return 'Erreur : ' . $this->getMessage() . ' dans le fichier ' . $this->getFile() . ' à la ligne ' . $this->getLine();
    }
}
