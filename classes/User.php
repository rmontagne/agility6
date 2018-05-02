<?php

require_once(dirname(__FILE__).'/ORM.php');

class User extends ORM {
    
    protected $id;
    protected $firstname;
    protected $lastname;
	protected $newAttribut;
    protected $age;
    
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
        $this->lastname = $lastname;
        return $this;
    }
    
    public function getAge() {
        return $this->age;
    }
    
    public function setAge($age) {
        $this->age = $age;
        return $this;
    }
}