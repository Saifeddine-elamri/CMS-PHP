<?php
// app/Models/Model.php

class Model {
    // Propriétés et méthodes communes pour tous les modèles
    public function save() {
        echo 'Sauvegarde de ' . get_class($this) . '\n';
    }
}
?>