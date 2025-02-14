<?php
// app/Models/Car.php
require 'Model.php';
class Car extends Model {
    public $age;
    public $price;
    public $color;


    public function __construct($age, $price, $color) {
        $this->age = $age;
        $this->price = $price;
        $this->color = $color;
    }
}
?>