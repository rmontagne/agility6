<?php

require_once(dirname(__FILE__).'/ORM.php');

class Product extends ORM {
    
    protected $name;
    protected $price;
    protected $qty;
    protected $description;
    protected $reference;
    protected $image;
    
    // GETTERS
    public function getName() {
        return $this->name;
    }
    
    public function getPrice() {
        return $this->price;
    }
    
    public function getQty() {
        return $this->qty;
    }
    
    public function getDescription() {
        return $this->description;
    }
    
    public function getReference() {
        return $this->reference;
    }
    
    public function getImage() {
        return $this->image;
    }
    
    // SETTERS
    public function setName($name) {
        if (is_string($name)) {
            $this->name = $name;
        }
        return $this;
    }
    
    public function setPrice($price) {
        $price = floatval($price);
        $this->price = $price;
        return $this;
    }  // utiliser plutôt intval ($string) / floatval($string)
    
    public function setQty($qty) {
        $qty = intval($qty);
        $this->qty = $qty;
        return $this;
    }
    
    public function setDescription($description) {
        if (is_string($description)) {
            $this->description = $description;
        }
        return $this;
    }
    
    public function setReference($reference) {
        $this->reference = $reference;
        return $this;
    }
    
    public function setImage($image) {
        if (is_string($image)) {
            $this->image = $image;
        }
        return $this;
    }
    
    public function getAttributes() {
        $attributes = get_class_vars(get_class($this));
        return $attributes;
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