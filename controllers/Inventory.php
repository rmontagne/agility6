<?php

require_once(dirname(__FILE__).'/../classes/DataBase/Mysql.php');
require_once(dirname(__FILE__).'/../classes/ORM/Product.php');
require_once(dirname(__FILE__).'/../classes/ORM/User.php');

class Inventory {
    
    public function Show() {
        $count = Product::getCount();

        if(isset($_POST['pagelength'])) {
            $length=$_POST['pagelength'];
        } else if(isset($_GET['pagelength'])){
            $length=$_GET['pagelength'];
        } else {
            $length = 10;
        }
        
        if(isset($_POST['page'])){
            $page = (int) $_POST['page'];
        } else if(isset($_GET['page'])) {
            $page = (int) $_GET['page'];
        } else {
            $page = 1;
        }     
               
        if($length=='Tous') {
            $lastPage = 1;
            $products = Product::getAllInstances();
            $pages=[1];
        } else {
            $start = ($page-1)*$length;
            $lastPage = intdiv($count, $length)+1;
            $products   = Product::getNInstances($start, $length-1);
            $pages=[];
            if($page==$lastPage OR $page==$lastPage-1) {
                for($i=$lastPage-4 ;$i<=$lastPage; $i++) {
                    $pages[]=$i;
                }
            } else if ($page==1 OR $page==2) {
                $pages=[1,2,3,4,5];
            } else {
                for($i=$page-2 ;$i<=$page+2; $i++) {
                    $pages[]=$i;
                }
            }
        }

        $access = $_GET['access'];
        
        $params = [
            'pages' => $pages,
            'pagelength' => $length,
            'lastpage' => $lastPage,
            'count' => $count,
            'products'  =>  $products,
            'access' => $access,
            'page' => $page 
        ];        
        
        if($_GET['access']=='back') {
            return Template::render('inventory', $params);
        } else if ($_GET['access']=='front') {
            return Template::render('catalogue', $params);
        }       
    }
    
    public function Add() {
        $length=$_GET['pagelength'];
        $page = $_GET['page'];
        $postVars = ['name','price','qty','description','reference','image'];
        $newProduct = new Product;
        foreach($postVars as $var) {
            if(!isset($_POST[$var])) {
                die('$_POST[\''.$var.'\'] non transmis');
            }
            $setter = 'set'.ucfirst($var);
            $newProduct->$setter($_POST[$var]);
        }
        $reference = $_POST['reference'];
        $productInstance = Product::getInstanceByRef($reference);
        if($productInstance != null) {
            die("Ce produit est déjà présent, utiliser le formulaire de modification");
        } else {
            $newProduct->add();
        }        
        header('Location: Inventory-Show?access=back&page='.$page.'&pagelength='.$length);
    }
    
    public function Delete() {
        $length=$_GET['pagelength'];
        $page = $_GET['page'];
        $id = $_GET['id'];
        $product = Product::getInstance($id);
        if($product == null) {
            die("l'id du produit n'existe pas");
        } else {
            $product->remove();   
        }
        header('Location: Inventory-Show?access=back&page='.$page.'&pagelength='.$length);
    }
    
    public function Updateform() {
        $length=$_GET['pagelength'];
        $page = $_GET['page'];
        $id = $_GET['id'];
        $product = Product::getInstance($id);
        if($product == null) {
            die("l'id du produit n'existe pas");
        } else {
            $params = [
                'product'  =>  $product,
                'page' => $page,
                'pagelength' => $length
            ];
            return Template::render('productUpdateForm', $params);
        }
    }
    
    public function Update() {
        $length=$_GET['pagelength'];
        $page = $_GET['page'];
        $id = $_GET['id'];

        $product = Product::getInstance($id);

        if($product == null) {
            die("l'id du produit n'existe pas");
        } else {
            $postVars = ['name','price','qty','description','reference','image'];
            foreach($postVars as $var) {
                if(!isset($_POST[$var])) {
                    die('$_POST[\''.$var.'\'] non transmis');
                }
                $setter = 'set'.ucfirst($var);
                $product->$setter($_POST[$var]);
            }

            $product-> update();
        }
        header('Location: Inventory-Show?access=back&page='.$page.'&pagelength='.$length);
    }
    
    public function Zoom() {
        $length=$_GET['pagelength'];
        $page = $_GET['page'];
        
        if(isset($_GET['id'])){
            $id = $_GET['id'];
        }
        $prod = Product::getInstance($id);
        if($prod == null) {
            die("l'id du produit n'existe pas");
        } else {
            $params = [
                'product'  =>  $prod,
                'page' => $page,
                'pagelength' => $length
            ];
            return Template::render('zoomprod', $params);
        }
    }     
}