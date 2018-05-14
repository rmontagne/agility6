<?php

//https://www.if-not-true-then-false.com/2010/php-class-for-coloring-php-command-line-cli-scripts-output-php-output-colorizing-using-bash-shell-colors/

    require_once(dirname(__FILE__).'/../classes/CmdColors.php');
    require_once(dirname(__FILE__).'/../classes/Product.php');
    require_once(dirname(__FILE__).'/../classes/CsvReader.php');
    require_once(dirname(__FILE__).'/../classes/Mysql.php');
    require_once(dirname(__FILE__).'/../classes/ORM.php');
    
    
    $colors             = new CmdColors();
    $file               = dirname(__FILE__).'/../import/products-arome-naturel.csv';
    $csvFile            = new CsvReader();
    $csvFile->setFileName($file);
    $csvArray           = $csvFile->readCsv();
    
    $products           = Product::getInstances();
    $firstLoop          = true;
    
    foreach ($csvArray as $row) {
        $csvRef         = intval($row['reference']);
        $currentProduct = Product::getFromReference($csvRef);
        
        if (!$currentProduct) {
            $currentProduct = new Product;
        }
        $currentProduct->setName($row['name']);
        $currentProduct->setPrice($row['price']);
        $currentProduct->setQty(0);
        $currentProduct->setDescription($row['description']);
        $currentProduct->setReference($csvRef);
        $currentProduct->setImage($row['image']);
        $currentProduct->save();
    }
    
    // Importer la table products-arome-naturel.csv dans la table products
    // Créer une classe Category qui implemante le tri par arbre binaire
    
    echo $colors->getColoredString("Testing Colors class, this is purple string on yellow background.", "purple", "yellow") . "\n";
    echo $colors->getColoredString("Testing Colors class, this is red string on black background.", "red", "black") . "\n";
    
    echo "\n";
    
