<?php

require_once(dirname(__FILE__).'/ORM.php');

class User extends ORM {
    
    protected $id;
    protected $firstname;
    protected $lastname;
    public $name='Guyve';
	protected $newAttribut;
        
    public function getFirstname() {
        return $this->firstname;
    }
    
    public function setFirstname($firstname) {
        $this->firstname = $firstname;
        return $this;
    }
       
    public function getLastname() {
        return $this->lasstname;
    }
    
    public function setLastname($lastname) {
        $this->laststname = $lastname;
        return $this;
    }
}