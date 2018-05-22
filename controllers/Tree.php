<?php

function autoLoader($classname)
{
    require_once (dirname(__FILE__).'/../classes/'.$classname.'.php');
}

spl_autoload_register('autoLoader');

class Tree {
    
    public function Test() {
        $products = Product::getInstances();
        $tree = new BinaryTree;
        
        foreach ($products as $key => $product){
           $tree->add($key, $product);
        }
        $subtree = $tree->getSubtree(1);
        $node = $tree->getNode(2);
        print_r($subtree);
    }
}