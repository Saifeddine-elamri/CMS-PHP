<?php
namespace App\Attributes;

#[\Attribute(\Attribute::TARGET_METHOD)]
class IsGranted {
    public function __construct(
        public string $role
    ) {}
}
