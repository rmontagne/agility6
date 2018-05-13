<?php

require_once(dirname(__FILE__).'/ORM.php');

class Product extends ORM {
    
    protected $name;
    protected $description;
    protected $reference;
    protected $price;
    protected $image;
    protected $qty;
    
    public static function exists($reference) {
        $table = strtolower(get_called_class());
        $query = 'SELECT COUNT(*) AS count FROM '.$table.' WHERE reference = '.$reference;
        $count = Mysql::getInstance()->getValue($query);
        return $count;
    }
    
    public static function getInstanceByRef($reference) {
        $table      = strtolower(get_called_class());
        $className  = get_called_class();
        $query      = 'SELECT * FROM '.$table.' WHERE reference = "'.$reference.'"';
        $row        = Mysql::getInstance()->getRow($query);
        if(is_null($row)) {
            return null;
        } else {
            $id = $row->id_product;
            $obj    = new $className($id);
            foreach($row as $property => $value) {
                if (property_exists($obj, $property)) {
                    $obj->$property = $value;
                }
            }
            return $obj;
        }
    }
    
    public function getReference() {
        return $this->reference;
    }
    
    public function setReference($reference) {
        $this->reference = $reference;
        return $this;
    }
    
    public function getName() {
        return $this->name;
    }
    
    public function setName($name) {
        $this->name = $name;
        return $this;
    }
    
    public function getDescription() {
        return $this->description;
    }
    
    public function setDescription($description) {
        $this->description = $description;
        return $this;
    }
    
    public function getPrice() {
        return $this->price;
    }
    
    public function setPrice($price) {
        $this->price = floatval($price);
        return $this;
    }
    
    public function getQty() {
        return $this->qty;
    }
    
    public function setQty($qty) {
        $this->qty = intval($qty) ;
        return $this;
    }
    
    public function getImage() {
        return $this->image;
    }
    
    public function setImage($image) {
        $this->image = $image;
        return $this;
    }
}