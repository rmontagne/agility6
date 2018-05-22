<?php

require_once(dirname(__FILE__).'/../classes/Product.php');

class Ajax {
    
    public function Rand() {
        return rand(0, 100);
    }
    
    public function Html() {
        return Template::render('hello', []);
    }
    
    public function Getproductprice() {
        
        $product = Product::getInstance($_POST['idProduct']);
        $json = ['price' => $product->getPrice(), 'name' => $product->getName()];
        
        exit(json_encode($json));
        
    }
    
    public function Json() {
        
        $productsJson   = array();
        $products       = Product::getInstances();
        
        foreach ($products as $product) {
            $productsJson[] = ['name' => $product->getName(), 'id' => $product->getId()];
        }
        
        echo json_encode($productsJson);
        
        exit();
    }
    
    public function Show() {
        return Template::render('ajax', []);
    }
    
}