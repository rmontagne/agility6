<?php

require_once(dirname(__FILE__).'/../classes/Mysql.php');
require_once(dirname(__FILE__).'/../classes/Product.php');
require_once(dirname(__FILE__).'/../classes/User.php');

class Hello {
    
    
    public function Loop() {
        
        $tabs = ['Romain', 'Romain3', 'Romain5'];
        
        return Template::render('loop', array(
            'tabs' => $tabs,
            'products' => Product::getInstances(),
            'users' => User::getInstances()
        ));
    }
    
    public function Show() {
        
        //GET ALL OBJECTS
        /*$products   = Product::getInstances();
        
        //READ | GET
        $product    = Product::getInstance(1);
        $user       = User::getInstance(2);
        
        //UPDATE
        //$product->setName('Iphone');
        //$product->update();
        
        //ADD
        $newP = new Product();
        $newP->setName('Iphone 10');
        $newP->add();
        
        //REMOVE
        $product->remove();*/
        
        if (isset($_POST['productName']) && isset($_POST['productPrice']) && isset($_POST['productQty'])) {
            $newP = new Product();
            $newP->setName($_POST['productName']);
            $newP->setPrice($_POST['productPrice']);
            $newP->setQty($_POST['productQty']);
            $newP->add();
            echo 'Le produit a été ajouté à la table.';
        }
        
        if (isset($_POST['userFirstname']) && isset($_POST['userLastname'])) {
            $newUser = new User();
            $newUser->setFirstname($_POST['userFirstname']);
            $newUser->setLastname($_POST['userLastname']);
            $newUser->add();
            echo 'L\'utilisateur a été ajouté à la table.';
        }
                
        return Template::render('hello', array(
            //'var'   => 'toto',
            //'user'  => $user,
            //'product' => $product
        ));  
    }
    
    public function Includetpl() {
        
        return ' <br />TEST REMOTE INCLUDE <br />';
        
    }
    
}