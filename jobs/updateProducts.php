<?php

//https://www.if-not-true-then-false.com/2010/php-class-for-coloring-php-command-line-cli-scripts-output-php-output-colorizing-using-bash-shell-colors/
require_once(dirname(__FILE__).'/../config.inc.php');
require_once(dirname(__FILE__).'/../classes/CmdColors.php');
require_once(dirname(__FILE__).'/../classes/ORM/Product.php');
require_once(dirname(__FILE__).'/../classes/Parser/Parser.php');
require_once(dirname(__FILE__).'/../classes/Parser/CSVParser.php');

//$colors = new CmdColors();

//HERE CODE
$path = dirname(__FILE__).'/../import/products-arome-naturel.csv';
$content = file_get_contents($path);
$headers = ['reference','name','description','price','image'];
$parser = new CSVParser($content, $headers);
$parser->parse();
$products = $parser->data();

$countNew=0;
$countOld=0;

foreach($products as $product) {
    $reference = $product['reference'];
    $productInstance = Product::getInstanceByRef($reference);

    if($productInstance == null) {
        
        $newProduct = new Product;
        $newProduct->setName($product['name']);
        $newProduct->setPrice($product['price']);
        $newProduct->setDescription($product['description']);
        $newProduct->setImage($product['image']);
        $newProduct->setReference($product['reference']);
        if($newProduct->add()) {
            $countNew+=1;
        }
    } else {
        if($productInstance->getName() != $product['name']) {
            $productInstance->setName($product['name']);
        }
        if($productInstance->getPrice() != $product['price']) {
            $productInstance->setPrice($product['price']);
        }
        if($productInstance->getDescription() != $product['description']) {
            $productInstance->setDescription($product['description']);
        }
        if($productInstance->getImage() != $product['image']) {
            $productInstance->setImage($product['image']);
        }
        if($productInstance->getReference() != $product['reference']) {
            $productInstance->setReference($product['reference']);
        }
        
        if($productInstance->update()) {
            $countOld+=1;
        }
    }
}

echo "ajouté : ".$countNew;
echo "modifié : ".$countOld;
    //Lister toutes les références du CSV
    // faire un requête pour comparer csv et bdd


//echo $colors->getColoredString("Testing Colors class, this is purple string on yellow background.", "purple", "yellow") . "\n";
//echo $colors->getColoredString("Testing Colors class, this is red string on black background.", "red", "black") . "\n";

echo "\n";