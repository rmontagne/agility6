<?php

abstract class DB {
    
    abstract protected function __construct();
    
    abstract public static function getInstance();
    abstract public function getResults($query, $params = []);
    abstract public function getRow($query, $params = []);
    abstract public function getValue($query, $params = []);
    abstract public function execute($query, $params = []);
     
}  