<?php

require_once(dirname(__FILE__).'/ORM.php');

class Product extends ORM {
    
    protected $id_category;
    protected $name;
    protected $description;
    protected $reference;
    protected $price;
    protected $image;
    protected $qty;
        
    public function getCategory() {
        return $this->id_category;
    }
    
    public function setCategory($id_category) {
        $this->id_category = $id_category;
        return $this;
    }
    
    public function getName() {
        return $this->name;
    }
    
    public function setName($name) {
        $this->name = $name;
        return $this;
    }
    
    public function getPrice() {
        return $this->price;
    }
    
    public function setPrice($price) {
        $this->price = $price;
        return $this;
    }
    
    public function getQty() {
        return $this->qty;
    }
    
    public function setQty($qty) {
        $this->qty = $qty;
        return $this;
    }
    
    public function getDescription() {
        return $this->description;
    }
    
    public function setDescription($description) {
        $this->description = $description;
        return $this;
    }
    
    public function getImage() {
        return $this->image;
    }
    
    public function setImage($image) {
        $this->image = $image;
        return $this;
    }
    
    public function getReference() {
        return $this->reference;
    }
    
    public function setReference($reference) {
        $this->reference = $reference;
        return $this;
    }
    
    public static function getFromReference($reference) {
        $query = 'SELECT id_product FROM product WHERE reference = "'.$reference.'"';
        $idProduct = Mysql::getInstance()->getValue($query);
        if (!$idProduct) {
            return false;
        } else {
            return Product::getInstance($idProduct->id_product);
        }
    }
    
}