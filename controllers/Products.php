<?php

require_once(dirname(__FILE__).'/../classes/Mysql.php');
require_once(dirname(__FILE__).'/../classes/Product.php');

class Products {
    
    
    public function List() {
        
        
        return Template::render('products', array(
            'products' => Product::getInstances()
        ));
    }
    
    public function Update() {
        $name           = strval($_POST['name']);
        $price          = floatval($_POST['price']);
        $qty            = intval($_POST['qty']);
        $table          = $_POST['table'];
        $id             = $_POST['id'];
        $reference      = strval($_POST['reference']);
        $description    = strval($_POST['description']);
        $image          = strval($_POST['image']);
        
        $product       = product::getInstance($id);
        
        $product->setName($name);
        $product->setPrice($price);
        $product->setQty($qty);
        $product->setReference($reference);
        $product->setDescription($description);
        if($image != '') {
        $product->setImage($image);
        }
        $product->update();
        echo "Product have been successfully updated";
    }
    
    public function Remove() {
        $id         = $_POST['id'];
        $product    = Product::getInstance($id);
        $product->remove();
        echo "Product have been successfully removed";  
    }
    
    public function Add() {
        $name           = strval($_POST['name']);
        $price          = floatval($_POST['price']);
        $qty            = intval($_POST['qty']);
        $reference      = strval($_POST['reference']);
        $description    = strval($_POST['description']);
        $image          = strval($_POST['image']);
        
        $newP = new Product();
        
        $newP->setName($name);
        $newP->setPrice($price);
        $newP->setQty($qty);
        $newP->setReference($reference);
        $newP->setDescription($description);
        $newP->setImage($image);
        $newP->Add();
        echo "Product have been successfully Added";
    }
}