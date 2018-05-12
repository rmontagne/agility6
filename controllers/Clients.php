<?php

require_once(dirname(__FILE__).'/../classes/DataBase/Mysql.php');
require_once(dirname(__FILE__).'/../classes/ORM/Product.php');
require_once(dirname(__FILE__).'/../classes/ORM/User.php');

class Clients {
    
    public function Show() {
        $users   = User::getInstances();
        $params = [
            'users'  =>  $users
        ];
        return Template::render('clients', $params);
    }
    
    public function Addclient() {
        if(isset($_POST['firstname']) AND isset($_POST['lastname'])){
            $newClient = new User;
            $newClient->setFirstname($_POST['firstname']);
            $newClient->setLastname($_POST['lastname']);
            $newClient->add();
        }
        header('Location: Clients-Show');
    }
    
    public function Deleteclient() {
        if(isset($_GET['id'])){
            $id = $_GET['id'];
            $user = User::getInstance($id);
            $user->remove();   
        }
        header('Location: Clients-Show');
    }
    
    public function Updateclientview() {
        if(isset($_GET['id'])){
            $id = $_GET['id'];
            $user = User::getInstance($id);
            $params = [
                'user'  =>  $user
            ];
            return Template::render('clientUpdateForm', $params);
        }
    }
    
    public function Updateclient() {
        if(isset($_GET['id'])){
            $id = $_GET['id'];
            if(isset($_POST['firstname']) AND isset($_POST['lastname'])){
                $newClient = User::getInstance($id);
                $newClient->setFirstname($_POST['firstname']);
                $newClient->setLastname($_POST['lastname']);
                $newClient-> update();
            } 
        }
        header('Location: Clients-Show');
    }
     
}