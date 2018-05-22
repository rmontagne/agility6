<?php

require_once("Node.php");

class BinaryTree {
    protected $root;
    
    public function __construct() {
        $this->root = null;
    }
    
    public function add($key, $data) {
        $node = new Node($key, $data);
        if ($this->root == null) {
            $this->root = $node;
        } else {
            $this->root->addNode($node);
        }
        
    }
    
    public function getSubtree($key){
        $key = $key+1;
        $subtree = $this->root->getSubtree($key);
        return $subtree;
    }
    
    public function getNode($key){
        $key = $key+1;
        $result = $this->root->getNode($key);
        return $result;
    }
    
}