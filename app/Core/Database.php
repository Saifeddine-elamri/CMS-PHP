<?php
namespace App\Core;

use PDO;

class Database {
    private static $instance = null;
    private $connection;

    // Empêche l'instanciation directe
    private function __construct(array $config) {
        $dsn = "{$config['driver']}:host={$config['host']};dbname={$config['database']};charset={$config['charset']}";
        $this->connection = new PDO($dsn, $config['username'], $config['password'], $config['options']);
    }

    // Retourne une instance unique de la base de données
    public static function init(array $config) {
        if (self::$instance === null) {
            self::$instance = new self($config);
        }
        return self::$instance;
    }

    // Retourne la connexion PDO
    public static function getConnection() {
        return self::$instance->connection;
    }
}