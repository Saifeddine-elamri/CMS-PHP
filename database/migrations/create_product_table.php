<?php
// Migration pour créer la table Product

class CreateProductTable {
    public function up($pdo) {
        // Requête SQL pour créer la table
        $sql = "CREATE TABLE IF NOT EXISTS `Product` (
";
        $sql .= "    `id` INT AUTO_INCREMENT PRIMARY KEY,
";
        $sql .= "    `name` VARCHAR(255),
`price` VARCHAR(255),
`quantity` VARCHAR(255),
";
        $sql .= "    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
";
        $sql .= "    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
";
        $sql .= ");";
        
        // Exécution de la requête SQL
        $pdo->exec($sql);
        echo 'Table `Product` créée avec succès.' . PHP_EOL;
    }

    public function down($pdo) {
        // Requête SQL pour supprimer la table
        $sql = "DROP TABLE IF EXISTS `Product`;";
        $pdo->exec($sql);
        echo 'Table `Product` supprimée avec succès.' . PHP_EOL;
    }
}
?>