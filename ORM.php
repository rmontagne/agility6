<?php
    
require_once(dirname(__FILE__).'/Mysql.php');

class ORM {
    
    /*
        Norme : 
        Class Name == table Name to lowercase
        ID = id_tableName
    */
    
    protected $table;
    protected $id;
    
    public function __construct($id = null) {
        $this->id = $id;
    }
    
    public static function getInstances() {
        $objs       = [];
        $className  = get_called_class();
        $table      = strtolower($className);
        
        $query      = 'SELECT id_'.$table.' as id FROM '.$table.' ORDER BY id ASC';
        
        $results    = Mysql::getInstance()->getResults($query);
        
        foreach ($results as $row) {
            $objs[] = self::getInstance($row->id);
        }
        
        return $objs;
    }
    
    public static function getInstance($id) {
        
        $className  = get_called_class();
        $table      = strtolower($className);
        $query      = 'SELECT * FROM '.$table.' WHERE id_'.$table.' = '.$id;
        
        $row        = Mysql::getInstance()->getRow($query);
        
        $obj        = new $className($id);
        $obj->table = $table;
        
        foreach($row as $property => $value) {
            if (property_exists($obj, $property)) {
                $obj->$property = $value;
            }
        }
        return $obj;      
    }
    
    public function getId() {
        return $this->id;
    }
    
    public function getTable() {
        return $this->table;
    }
    
    //ADD NEW OBJECT ON DB
    public function add() {
        $className  = get_called_class();
        $table      = strtolower($className);
        $vars       = get_object_vars($this);
        $fields     = [];
        $properties = [];
        $values     = [];
        
        foreach($vars as $property => $value) {
            if (in_array($property, ['id', 'table'])) {
                continue;
            }
            $properties[]   = $property;
            $fields[]       = $value;
            $values[]       = '?';
        }
        //die(print_r($fields));
        $query = 'INSERT INTO '.$table.'('.implode(',', $properties).') VALUES('.implode(',', $values).')';
        Mysql::getInstance()->execute($query,$fields);
    }
    
    //REMOVE OBJECT FROM DB
    public function remove() {
            $query = 'DELETE FROM '.$this->table.' WHERE id_'.$this->table.' = '.$this->id;
            Mysql::getInstance()->execute($query);
    }
    
    public function update() {
        $vars   = get_object_vars($this);
        $fields = [];
        
        foreach ($vars as $property => $value) {
            if (in_array($property, ['id', 'table'])) {
                continue;
            }
           $fields[] = $property.' = "'.$value.'"';
        }
        
        $query  = 'UPDATE '.$this->table.' SET '.implode(',' , $fields).' WHERE id_'.$this->table.' = '.$this->id;
        
        Mysql::getInstance()->execute($query);
    }
    
    public function save() {
        if ($this->id) {
            return $this->update();
        } else {
            return $this->add();
        }
    }
    
}