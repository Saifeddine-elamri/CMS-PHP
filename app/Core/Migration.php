<?php
// orm/Migration.php
require_once 'Database.php';

class Migration {
    public static function createTable($tableName, $columns) {
        $pdo = Database::getInstance();
        $fields = [];
        foreach ($columns as $column => $type) {
            $fields[] = "$column $type";
        }
        $sql = "CREATE TABLE IF NOT EXISTS $tableName (" . implode(', ', $fields) . ")";
        $pdo->exec($sql);
    }
}
?>
