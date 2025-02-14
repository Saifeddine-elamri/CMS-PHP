<?php
// src/Core/Http.php
namespace App\Core;

#[\Attribute(\Attribute::TARGET_METHOD)]
class Http {
    public function __construct(
        public string $method // GET, POST, PUT, DELETE, etc.
    ) {}
}
