<?php

//https://www.if-not-true-then-false.com/2010/php-class-for-coloring-php-command-line-cli-scripts-output-php-output-colorizing-using-bash-shell-colors/
require_once(dirname(__FILE__).'/../config.inc.php');
require_once(dirname(__FILE__).'/../classes/CmdColors.php');
require_once(dirname(__FILE__).'/../classes/Product.php');
require_once(dirname(__FILE__).'/../classes/Parser.php');
require_once(dirname(__FILE__).'/../classes/CSVParser.php');

//$colors = new CmdColors();

//HERE CODE
echo "Hello World!";

$path = dirname(__FILE__).'/../import/products-arome-naturel.csv';
$content = file_get_contents($path);
$headers = ['reference','name','description','price','image'];
$parser = new CSVParser($content, $headers);
$parser->parse();
$products = $parser->data();
foreach($products as $product) {
    $newProduct = new Product;
    // ajouter une condition sur la référence
    //$id= $newProduct->getReference();
    //Product::getInstance($reference);
    
    /*foreach($product as $key => $value) {
        $setter = 'set'.ucfirst($key);
        //pas forcémént mettre le générique, plutôt mettre tous les setters
        if (method_exists($newProduct, $setter)) {
            $newProduct->$setter($value);
        }
    }*/
    
    $newProduct->setName($product['name']);
    $newProduct->setPrice($product['price']);
    $newProduct->setQty($product['qty']);
    $newProduct->setDescription($product['description']);
    $newProduct->setReference($product['reference']);
    $newProduct->setImage($product['image']);
   
    echo (var_dump($newProduct->getName()));
    $newProduct->add();
    echo "ajouté \n";
    
    //Lister toutes les références du CSV
    // faire un requête pour compoarer csv et bdd
}

//echo $colors->getColoredString("Testing Colors class, this is purple string on yellow background.", "purple", "yellow") . "\n";
//echo $colors->getColoredString("Testing Colors class, this is red string on black background.", "red", "black") . "\n";

echo "\n";