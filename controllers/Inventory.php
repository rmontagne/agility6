<?php

require_once(dirname(__FILE__).'/../classes/Mysql.php');
require_once(dirname(__FILE__).'/../classes/Product.php');
require_once(dirname(__FILE__).'/../classes/User.php');

class Inventory {
    
    public function Show() {
        $products   = Product::getInstances();
        $params = [
            'products'  =>  $products
        ];
        return Template::render('products', $params);
    }
    
    public function Addproduct() {
        if(isset($_POST['name']) AND isset($_POST['price']) AND isset($_POST['qty'])){
            $newProduct = new Product;
            $newProduct->setName($_POST['name']);
            $newProduct->setPrice($_POST['price']);
            $newProduct->setQty($_POST['qty']);
            $newProduct->add();
        }
        header('Location: Inventory-Show');
    }
    
    public function Deleteprod() {
        if(isset($_GET['id'])){
            $id = $_GET['id'];
            $prod = Product::getInstance($id);
            $prod->remove();   
        }
        header('Location: Inventory-Show');
    }
    
    public function Updateprodview() {
        if(isset($_GET['id'])){
            $id = $_GET['id'];
            $prod = Product::getInstance($id);
            $params = [
                'product'  =>  $prod
            ];
            return Template::render('productUpdateForm', $params);
        }
    }
    
    public function Updateprod() {
        if(isset($_GET['id'])){
            $id = $_GET['id'];
            if(isset($_POST['name']) AND isset($_POST['price']) AND isset($_POST['qty'])){
                $product = Product::getInstance($id);
                $product->setName($_POST['name']);
                $product->setPrice($_POST['price']);
                $product->setQty($_POST['qty']);
                $product-> update();
            } 
        }
        header('Location: Inventory-Show');
    }
    
}