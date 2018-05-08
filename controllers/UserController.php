<?php

require_once(dirname(__FILE__).'/../classes/Mysql.php');
require_once(dirname(__FILE__).'/../classes/Product.php');
require_once(dirname(__FILE__).'/../classes/User.php');

    class Usercontroller {
        
        public function Show() {
            $users   = User::getInstances();
            $params = [
                'users'  =>  $users
            ];
            foreach($users as $user) {
                $params [$user->getId()] = $user;
            }
            return Template::render('users', $params);
            return 'ON USER';
            
        }
        
    }