<?php

require_once(dirname(__FILE__).'/ORM.php');

class User extends ORM {
    
    protected $id;
    protected $firstname;
    protected $lastname;
    protected $age;
    public $name='Guyve';

        
    public function getFirstname() {
        return $this->firstname;
    }
    
    public function setFirstname($firstname) {
        $this->firstname = $firstname;
        return $this;
    }
       
    public function getLastname() {
        return $this->lastname;
    }
    
    public function setLastname($lastname) {
        $this->laststname = $lastname;
        return $this;
    }
}