<?php

require_once(dirname(__FILE__).'/../classes/Mysql.php');
require_once(dirname(__FILE__).'/../classes/Product.php');
require_once(dirname(__FILE__).'/../classes/User.php');
require_once(dirname(__FILE__).'/../classes/Parser.php');
require_once(dirname(__FILE__).'/../classes/CSVParser.php');

class Import {
    
    public function Add() {
        
        $path = dirname(__FILE__).'/../import/products-arome-naturel.csv';
        $content = file_get_contents($path);
        
        $parser = new CSVParser($content);
        $parser->parse();
        $products = $parser->data();

        foreach($products as $product) {
           
            $newProduct = new Product;
            foreach($product as $key => $value) {
                $setter = 'set'.ucfirst($key);
                echo var_dump($setter);
                if (method_exists($newProduct, $setter)) {
                    echo var_dump($setter);
                    $newProduct->$setter($value);
                }
            }            
        }
        //        return Template::render('import', $params);
    }
   

     
}