<?php
// Migration pour créer la table Animal

class CreateAnimalTable {
    public function up($pdo) {
        // Requête SQL pour créer la table
        $sql = "CREATE TABLE IF NOT EXISTS `Animal` (
";
        $sql .= "    `id` INT AUTO_INCREMENT PRIMARY KEY,
";
        $sql .= "    `name` VARCHAR(255),
`age` VARCHAR(255),
`race` VARCHAR(255),
";
        $sql .= "    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
";
        $sql .= "    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
";
        $sql .= ");";
        
        // Exécution de la requête SQL
        $pdo->exec($sql);
        echo 'Table `Animal` créée avec succès.' . PHP_EOL;
    }

    public function down($pdo) {
        // Requête SQL pour supprimer la table
        $sql = "DROP TABLE IF EXISTS `Animal`;";
        $pdo->exec($sql);
        echo 'Table `Animal` supprimée avec succès.' . PHP_EOL;
    }
}
?>