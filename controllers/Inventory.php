<?php

require_once(dirname(__FILE__).'/../classes/DataBase/Mysql.php');
require_once(dirname(__FILE__).'/../classes/ORM/Product.php');
require_once(dirname(__FILE__).'/../classes/ORM/User.php');

class Inventory {
    
    public function Show() {
        $count = Product::getCount();
        $lastPage = intdiv($count, 10)+1;
        
        if(isset($_POST['page'])){
            $page = (int) $_POST['page'];
        } else if(isset($_GET['page'])) {
            $page = (int) $_GET['page'];
        } else {
            $page = 1;
        }
        
        $start = ($page-1)*10;
        $products   = Product::get10Instances($start);
        /*$product   = Product::getInstance(1);
        die(var_dump($products));*/
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
        $access = $_GET['access'];
        $params = [
            'pages' => $pages,
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
    
    public function Addproduct() {
        $access = $_GET['access'];
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
            die("Ce prduit est déjà présent, utiliser le formulaire de modification");
        } else {
            $newProduct->add();
        }
        
        header('Location: Inventory-Show?access='.$access.'&page='.$page);
    }
    
    public function Deleteprod() {
        $access = $_GET['access'];
        $page = $_GET['page'];
        $id = $_GET['id'];
        $prod = Product::getInstance($id);
        if($prod == null) {
            die("l'id du produit n'existe pas");
        } else {
            $prod->remove();   
        }
        header('Location: Inventory-Show?access='.$access.'&page='.$page);
    }
    
    public function Updateprodview() {
        $page = $_GET['page'];
        $id = $_GET['id'];

        $prod = Product::getInstance($id);

        if($prod == null) {
            die("l'id du produit n'existe pas");
        } else {
            $params = [
                'product'  =>  $prod,
                'page' => $page
            ];
            return Template::render('productUpdateForm', $params);
        }
    }
    
    public function Updateprod() {

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
        header('Location: Inventory-Show?access=back&page='.$page);
    }
    
    public function Zoomprod() {
        if(isset($_GET['id'])){
            $id = $_GET['id'];
        }
        $prod = Product::getInstance($id);
        if($prod == null) {
            die("l'id du produit n'existe pas");
        } else {
            $params = [
                'product'  =>  $prod
            ];
            return Template::render('zoomprod', $params);
        }
    }
     
}