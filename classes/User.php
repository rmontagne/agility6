<?php

require_once(dirname(__FILE__).'/ORM.php');

class User extends ORM {
    
    protected $id;
    protected $firstname;
    protected $lastname;
        
    public function getFirstname() {
        return $this->firstname;
    }
       
}