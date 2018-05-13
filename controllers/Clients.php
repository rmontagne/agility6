<?php

require_once(dirname(__FILE__).'/../classes/DataBase/Mysql.php');
require_once(dirname(__FILE__).'/../classes/ORM/Product.php');
require_once(dirname(__FILE__).'/../classes/ORM/User.php');

class Clients {
    
    public function Show() {
        $count = User::getCount();
        
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
            $users = User::getAllInstances();
            $pages=[1];
        } else {
            $start = ($page-1)*$length;
            $lastPage = intdiv($count, $length)+1;
            $users   = User::getNInstances($start, $length-1);
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
        
        $params = [
            'pages' => $pages,
            'pagelength' => $length,
            'lastpage' => $lastPage,
            'count' => $count,
            'users'  =>  $users,
            'page' => $page
        ];
        
        return Template::render('clients', $params);
    }
    
    public function Add() {
        $length=$_GET['pagelength'];
        $page = $_GET['page'];
        $postVars = ['firstname','lastname','phone','email','avatar'];
        $newClient = new User;
        foreach($postVars as $var) {
            if(!isset($_POST[$var])) {
                die('$_POST[\''.$var.'\'] non transmis');
            }
            $setter = 'set'.ucfirst($var);
            $newClient->$setter($_POST[$var]);
        }
        $email = $_POST['email'];
        $clientInstance = User::getInstanceByEmail($email);
        if($clientInstance != null) {
            die("Ce mail est déjà pris");
        } else {
            $newClient->add();
        }
        header('Location: Clients-Show?page='.$page.'&pagelength='.$length);
    }
    
    public function Delete() {
        $length=$_GET['pagelength'];
        $page = $_GET['page'];
        $id = $_GET['id'];
        $user = User::getInstance($id);
        if($user == null) {
            die("l'id du client n'existe pas");
        } else {
            $user->remove();
        }
        header('Location: Clients-Show?access=back&page='.$page.'&pagelength='.$length);
    }
    
    public function Updateform() {
        $length=$_GET['pagelength'];
        $page = $_GET['page'];
        $id = $_GET['id'];
        $user = User::getInstance($id);
        if($user == null) {
            die("l'id du client n'existe pas");
        } else {
            $params = [
                'user'  =>  $user,
                'page' => $page,
                'pagelength' => $length
            ];
            return Template::render('clientUpdateForm', $params);
        }

    }
    
    public function Update() {
        $length=$_GET['pagelength'];
        $page = $_GET['page'];
        $id = $_GET['id'];        
        $user = User::getInstance($id);        
        if($user == null) {
            die("l'id du client n'existe pas");
        } else {
            $postVars = ['firstname','lastname','phone','email','avatar'];
            foreach($postVars as $var) {
                if(!isset($_POST[$var])) {
                    die('$_POST[\''.$var.'\'] non transmis');
                }
                $setter = 'set'.ucfirst($var);
                $user->$setter($_POST[$var]);
            }            
            $user-> update();
        }
        header('Location: Clients-Show?access=back&page='.$page.'&pagelength='.$length);
    }
    
    public function Zoom() {
        $length=$_GET['pagelength'];
        $page = $_GET['page'];
        
        if(isset($_GET['id'])){
            $id = $_GET['id'];
        }
        $user = User::getInstance($id);
        if($user == null) {
            die("l'id du client n'existe pas");
        } else {
            $params = [
                'user'  =>  $user,
                'page' => $page,
                'pagelength' => $length
            ];
            return Template::render('zoomclient', $params);
        }
    }     
}