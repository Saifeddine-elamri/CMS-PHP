<?php
require_once 'Model.php';

class Article extends Model {
    protected $table = 'articles';
    protected $primaryKey = 'id';
    protected $fillable = ['title', 'content'];
}
?>
