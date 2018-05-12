<?php

require_once(dirname(__FILE__).'/DB.php');

class Mysql extends DB {
    
    private $_PDOInstance;
    private static $_instance = null;
    
    protected function __construct() 
    {
        try 
        {
            $options =  [
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8", 
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_EMULATE_PREPARES => false 
            ]; 
        
            $this->_PDOInstance = new PDO('mysql:host='.HOST.';dbname='.DATABASE, USER, PASSWORD, $options);
        } catch (PDOException $e) {
            exit($e->getMessage() ); 
        }
    }
     
    public static function getInstance()
    {
        if(is_null(self::$_instance)) {
            self::$_instance = new Mysql();
        }
        return self::$_instance; 
    }

    public function getResults($query, $params = [])
    {
        $statement = $this->_PDOInstance->prepare($query);
        $statement->execute($params);
        $result = $statement->fetchAll(PDO::FETCH_OBJ); 
        $statement ->closeCursor();
        return $result;
    }
    
    public function getRow($query, $params = [])
    {
        $statement = $this->_PDOInstance->prepare($query.' LIMIT 1');
        $statement->execute($params);
        $result = $statement->fetchAll(PDO::FETCH_OBJ);
        $statement ->closeCursor();
        
        if (!empty($result)) {
            return $result[0];
        } else {
            return null;
        }
    }
       
    public function getValue($query, $params = [])
    {
        $statement = $this->_PDOInstance->prepare($query);
        $statement->execute($params);
        $result = $statement->fetch(); 
        $statement ->closeCursor();
        return $result[0]; 
    }
         
    public function execute($query, $params = [])
    {
        $statement = $this->_PDOInstance->prepare($query);
        $return=$statement->execute($params);
        return $return;
        //retourne true/false 
    }
    
    public function lastInsertId() {
        return $this->_PDOInstance->lastInsertId();
    }   
}
