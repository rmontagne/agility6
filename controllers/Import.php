<?php

require_once(dirname(__FILE__).'/../classes/Mysql.php');
require_once(dirname(__FILE__).'/../classes/Product.php');
require_once(dirname(__FILE__).'/../classes/User.php');
require_once(dirname(__FILE__).'/../classes/Parser.php');
require_once(dirname(__FILE__).'/../classes/CSVParser.php');

class Import {
    
    public function Show() {
        echo 'ON USER Show';
    }
    public function Add() {
        
        $path = dirname(__FILE__).'/../import/products-arome-naturel.csv';
        $content = file_get_contents($path);
        $headers = ['reference','name','description','price','image'];
        $parser = new CSVParser($content,$headers);
        $parser->parse();
        $products = $parser->data();
        foreach($products as $product) {
            $newProduct = new Product;
            foreach($product as $key => $value) {
                $setter = 'set'.ucfirst($key);
               
                if (method_exists($newProduct, $setter)) {
                    $newProduct->$setter($value);
                }
            }
            echo var_dump($newProduct);
            $newProduct->add();
        }
        
        //        return Template::render('import', $params);
    }
   

     
}