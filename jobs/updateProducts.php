<?php

//https://www.if-not-true-then-false.com/2010/php-class-for-coloring-php-command-line-cli-scripts-output-php-output-colorizing-using-bash-shell-colors/

require_once(dirname(__FILE__).'/../classes/CmdColors.php');
require_once(dirname(__FILE__).'/../classes/Product.php');


$colors = new CmdColors();

//HERE CODE


echo $colors->getColoredString("Testing Colors class, this is purple string on yellow background.", "purple", "yellow") . "\n";
echo $colors->getColoredString("Testing Colors class, this is red string on black background.", "red", "black") . "\n";

echo "\n";