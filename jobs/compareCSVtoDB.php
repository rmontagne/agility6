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

$newProductsRef=[];
$oldProductsRef=[];
$countNew=0;
$countOld=0;

foreach($products as $product) {
    $reference = $product['reference'];
    if(Product::exists($reference)!=0) {
        $oldProductsRef[]=$reference;
    } else {
        $newProductsRef[]=$reference;
    }
}
$countNew=count($newProductsRef);
$countOld=count($oldProductsRef);

echo "\n";
echo "Entrées ajoutées : ".$countNew;
echo "\n";
foreach($newProductsRef as $ref) { 
    echo $ref.', ';
}
echo "\n";
echo "Entrées modifiées : ".$countOld;
echo "\n";
foreach($oldProductsRef as $ref) { 
    echo $ref.', ';
}

    //Lister toutes les références du CSV
    // faire un requête pour comparer csv et bdd


//echo $colors->getColoredString("Testing Colors class, this is purple string on yellow background.", "purple", "yellow") . "\n";
//echo $colors->getColoredString("Testing Colors class, this is red string on black background.", "red", "black") . "\n";

echo "\n";