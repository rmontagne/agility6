<?php

require_once(dirname(__FILE__).'/ORM.php');

class User extends ORM {
    
    protected $firstname;
    protected $lastname;
    protected $email;
    protected $phone;
    protected $country;
    protected $avatar;
    
    public static function getInstanceByEmail($email) {
        $table      = strtolower(get_called_class());
        $className  = get_called_class();
        $query      = 'SELECT * FROM '.$table.' WHERE email = "'.$email.'"';
        $row        = Mysql::getInstance()->getRow($query);
        if(is_null($row)) {
            return null;
        } else {
            $id = $row->id_user;
            $obj    = new $className($id);
            foreach($row as $property => $value) {
                if (property_exists($obj, $property)) {
                    $obj->$property = $value;
                }
            }
            return $obj;
        }
    }
    
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
    
    public function getEmail() {
        return $this->email;
    }
    
    public function setEmail($email) {
        $this->email = $email;
        return $this;
    }
    
    public function getPhone() {
        return $this->phone;
    }
    
    public function setPhone($phone) {
        $this->phone = $phone;
        return $this;
    }
    
    public function getCountry() {
        return $this->country;
    }
    
    public function setCountry($country) {
        $this->country = $country;
        return $this;
    }
    
    public function getAvatar() {
        return $this->avatar;
    }
    
    public function setAvatar($avatar) {
        $this->avatar = $avatar;
        return $this;
    }      
}