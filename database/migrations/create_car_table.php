<?php
// Migration pour créer la table Car

class CreateCarTable {
    public function up($pdo) {
        // Requête SQL pour créer la table
        $sql = "CREATE TABLE IF NOT EXISTS `Car` (
";
        $sql .= "    `id` INT AUTO_INCREMENT PRIMARY KEY,
";
        $sql .= "    `age` VARCHAR(255),
`price` VARCHAR(255),
`color` VARCHAR(255),
";
        $sql .= "    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
";
        $sql .= "    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
";
        $sql .= ");";
        
        // Exécution de la requête SQL
        $pdo->exec($sql);
        echo 'Table `Car` créée avec succès.' . PHP_EOL;
    }

    public function down($pdo) {
        // Requête SQL pour supprimer la table
        $sql = "DROP TABLE IF EXISTS `Car`;";
        $pdo->exec($sql);
        echo 'Table `Car` supprimée avec succès.' . PHP_EOL;
    }
}
?>