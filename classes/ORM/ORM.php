<?php
    
require_once(dirname(__FILE__).'/../DataBase/Mysql.php');

class ORM {
    /*
        Norme : 
        Class Name == table Name to lowercase
        ID = id_tableName
    */
    private $table;
    private $id;
    private $cdate;
    private $mdate;
    
    public function __construct($id = null) {
        $this->id   = $id;
        $this->table = strtolower(get_called_class());     
    }
    
    public function getId(){
        return $this->id;
    }
    
    public function getCdate(){
        return $this->cdate;
    }
    
    public function getMdate(){
        return $this->mdate;
    }
    
    public static function getCount() {
        $table      = strtolower(get_called_class());
        $query      = 'SELECT COUNT(*)  AS count FROM '.$table;
        $count      = Mysql::getInstance()->getValue($query);
        return $count;
    }
    
    public static function get10Instances($start) {
        $objs       = [];
        $table      = strtolower(get_called_class());
        $query      = 'SELECT id_'.$table.' as id FROM '.$table .' ORDER BY id ASC LIMIT '.$start.', 9;';
        
        $results    = Mysql::getInstance()->getResults($query);
        
        foreach ($results as $row) {
            $objs[] = self::getInstance($row->id);
        }
        
        return $objs;
    }
    
    public static function getInstances() {
        $objs       = [];
        $table      = strtolower(get_called_class());
        $query      = 'SELECT id_'.$table.' as id FROM '.$table .' ORDER BY id DESC LIMIT 9;';
        
        $results    = Mysql::getInstance()->getResults($query);
        
        foreach ($results as $row) {
            $objs[] = self::getInstance($row->id);
        }
        
        return $objs;
    }
    
    // récupérer une instance à partir d'une entrée db par id
    public static function getInstance($id) {
        
        $className  = get_called_class();
        $table      = strtolower($className);
        
        $query  = 'SELECT * FROM '.$table.' WHERE id_'.$table.' = '.$id;
        
        $row    = Mysql::getInstance()->getRow($query);
        if(is_null($row)) {
            return null;
        } else {
            $obj    = new $className($id);
            
            foreach($row as $property => $value) {
                if (property_exists($obj, $property)) {
                    $obj->$property = $value;
                }
            }
            return $obj;
        }     
    }
  
    //ADD NEW OBJECT ON DB
    public function add() {
        
        $vars   = get_object_vars($this);
        $properties = [];
        $values=[];
        
        
        foreach ($vars as $property => $value) {
            if (in_array($property, ['id', 'table', 'qty','mdate','cdate'])) {
                continue;
            }
            /*
            if($value == null) {
                die('Propriété '.$property.' non définie, ajout objet incomplet impossible');
            }*/
            $properties[] = $property;
            $values[] = '"'.$value.'"';
        }
        $properties[]='cdate';
        $properties[]='mdate';
        $values[] = 'NOW()';
        $values[] = 'NOW()';
        $query  = 'INSERT INTO '.$this->table.' ('.implode(',' , $properties).') VALUES ('.implode(',' , $values).')';  
        $return = Mysql::getInstance()->execute($query);
        if($return) { 
            $this->id = Mysql::getInstance()->lastInsertId();
        }

        return $return;
    }
    
    //REMOVE OBJECT FROM DB
    public function remove() {
        $query = 'DELETE FROM '.$this->table.' WHERE id_'.$this->table.' = '.$this->id;
        return Mysql::getInstance()->execute($query);;
    }
    
    //UPDATE OBJECT IN DB
    public function update() {
    
        $vars   = get_object_vars($this);
        $fields = [];
        foreach ($vars as $property => $value) {
            if (in_array($property, ['id', 'table'])) {
                continue;
            }
            if($value == null) {
                continue;
            }
            $fields[] = $property.' = "'.$value.'"';
        }
        
        $query  = 'UPDATE '.$this->table.' SET '.implode(',' , $fields).' WHERE id_'.$this->table.' = '.$this->id;

        return Mysql::getInstance()->execute($query);
    }

}