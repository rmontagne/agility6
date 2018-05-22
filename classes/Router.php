<?php
    
    class Router {
        
        public static function run() {
            if(!isset($_GET['controller'])){
                $controller ="Hello";
            } else {
                $controller = $_GET['controller'];
            }
            if(!isset($_GET['action'])) {
                $action ="Show";
            } else {
            $action     = $_GET['action'];
            }
            echo self::callController($controller, $action);   
        }
        
        public static function callController($controller, $action) {
                        
            //CHECK IF CONTROLLER FILE EXIST
            if (!file_exists(dirname(__FILE__).'/../controllers/'.$controller.'.php')) {
                die('CONTROLLER NOT FOUND');
            }
            
            require_once(dirname(__FILE__).'/../controllers/'.$controller.'.php');
           
            //CHECK IF CLASS EXIST ON FILE
            if (!class_exists($controller)) {
                die('CLASS NOT EXIST');
            }
            
            $controllerObject = new $controller();
            
            if (!method_exists($controllerObject, $action)) {
                die('METHOD/ACTION NOT EXIST');
            }
            
            return call_user_func(array($controllerObject, $action));   
        }
        
    } 
    