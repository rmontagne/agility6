<?php
shell_exec('sudo ln -s /Applications/MAMP/tmp/mysql /var/mysql');
//https://www.if-not-true-then-false.com/2010/php-class-for-coloring-php-command-line-cli-scripts-output-php-output-colorizing-using-bash-shell-colors/

require_once(dirname(__FILE__).'/../classes/Product.php');
require_once(dirname(__FILE__).'/../classes/ORM.php');
require_once(dirname(__FILE__).'/../classes/CsvReader.php');



$parser = new CsvReader();
$rows = $parser-> ParseCsv(dirname(__FILE__).'/../import/products-arome-naturel.csv');
$products = Product::getInstances();

foreach ($rows as $array) {
    $ref = $array[0];
    if($ref !='') {
        $currentProduct = Product::getFromReference($ref);
        
        if (!$currentProduct) {
            $currentProduct = new Product;
        }
        $name = $array[1];
        $description = $array[2];
        $price = $array[3];
        $image = $array[4];
        $currentProduct->setName($name);
        $currentProduct->setPrice($price);
        $currentProduct->setDescription($description);
        $currentProduct->setReference($ref);
        $currentProduct->setImage($image);
        $currentProduct->save();
    }
}
    

/*
echo "We found ".count($result)." new entry\n";
echo "Want to Update database?  Type 'yes' to continue: ";
$handle = fopen ("php://stdin","r");
$line = fgets($handle);
if(trim($line) != 'yes'){
    echo "ok..so we are ABORTING!\n";
    exit;
}
fclose($handle);
echo "\n";
echo "Thank you, updating database...\n";
foreach ($rows as $array) {
    $ref = $array[0];
    foreach($result as $references){
        if($ref == $references) {
            
            $newP = new Product();
            $newP->setName($name);
            $newP->setPrice($price);
            $newP->setReference($ref);
            $newP->setDescription($description);
            $newP->setImage($image);
            $newP->Add();
        }
    }
}
*/