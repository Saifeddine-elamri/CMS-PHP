<?php
// app/Models/Product.php

class Product extends Model {
    public $name;
    public $price;
    public $quantity;


    public function __construct($name, $price, $quantity) {
        $this->name = $name;
        $this->price = $price;
        $this->quantity = $quantity;
    }
}
?>