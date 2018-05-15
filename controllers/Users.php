<?php

require_once(dirname(__FILE__).'/../classes/Mysql.php');
require_once(dirname(__FILE__).'/../classes/User.php');

class Users {
    
    
    public function List() {
        
        
        return Template::render('users', array(
            'users' => User::getInstances()
        ));
        
        
    }
    public function Update() {
        $firstname  = strval($_POST['firstname']);
        $lastname   = strval($_POST['lastname']);
        $table      = $_POST['table'];
        $id         = $_POST['id'];
        
        $user       = User::getInstance($id);
        
        $user->setFirstname($firstname);
        $user->setLastname($lastname);
        $user->update();
        echo "User have been successfully updated";
    }
    
    public function Remove() {
        $id         = $_POST['id'];
        $user    = User::getInstance($id);
        $user->remove();
        echo "User have been successfully removed";
    }
    
    public function Add() {
        $firstname         = strval($_POST['firstname']);
        $lastname          = strval($_POST['lastname']);
        
        $newU = new User();
        
        $newU->setFirstname($firstname);
        $newU->setLastname($lastname);
        $newU->Add();
        echo "User have been successfully Added";
    }
}
