<?php

require_once(dirname(__FILE__).'/../classes/DataBase/Mysql.php');
require_once(dirname(__FILE__).'/../classes/ORM/Product.php');
require_once(dirname(__FILE__).'/../classes/ORM/User.php');

class Hello {

    
    public function Show() {

        $customer = new stdClass();
        $customer->name = new stdClass();
        $customer->since = 45;
        $customer->name->firstname = 'Henri';
        $customer->name->lastname = 'Le Lion';
        
        $customer2 = new stdClass();
        $customer2->name = new stdClass();
        $customer2->since = 62;
        $customer2->name->firstname = 'Louis';
        $customer2->name->lastname = 'Le Brave';
        
        $customers = [$customer, $customer2];
        
        $guest1 = 'Riri';
        $guest2 = 'Fifi';
        $guest3 = 'Loulou';
        
        $guests = [$guest1, $guest2, $guest3];
        
        $user = User::getInstance(2);
        $products = Product::getInstances(1);
        $params = [
            'user'  =>  $user,
            'test'  =>  '0',
            'tes2'  =>  'alpha',
            'customer' => $customer,
            'customers' => $customers,
            'guests'=> $guests
        ];       
        return Template::render('hello', $params);  
    }
    
    public function Includetpl() {
        
        return ' <br />TEST REMOTE INCLUDE <br />';
        
    }

}