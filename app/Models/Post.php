<?php
// app/Models/Post.php

class Post extends Model {
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