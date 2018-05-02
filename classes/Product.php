<?php

require_once(dirname(__FILE__).'/ORM.php');

class Product extends ORM {
    
    protected $name;
    protected $price;
    protected $qty;
        
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
    
}