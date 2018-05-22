<?php

require_once(dirname(__FILE__).'/ORM.php');

class Category extends ORM {
    
    protected $name;
    protected $image;
    
    public function getName() {
        return $this->name;
    }
    
    public function setName($name) {
        $this->name = $name;
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