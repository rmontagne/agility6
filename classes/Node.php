<?php

require_once("BinaryTree.php");
require_once("Product.php");

class Node {
    public $key;
    public $data;
    public $left;
    public $right;
    
    public function __construct($key, $data) {
        $this->key = $key+1;
        $this->data = $data;
        $this->left = null;
        $this->right = null;
    }
    public function addNode($node) {
        if ($node->key == 2*($this->key)) {
            $this->left = $node;
        }
        elseif ($node->key == (2*($this->key))+1) {
            $this->right = $node;
        } else {
            for($i = 1 ; $i<20 ; $i++){
                $leftmost = 0;
                $rightmost= 0;
                $leftmost =bcpow(2, $i)*$this->key;
                $rightmost =(bcpow(2, $i)*$this->key) + (bcpow(2, $i) -1);
                if($node->key >= $leftmost AND $node->key <= $rightmost) {
                    break;
                } 
            }
            $leftOrright = ($leftmost + $rightmost)/2;
            if($node->key < $leftOrright){
                $this->left->addNode($node);
            }
            if($node->key > $leftOrright){
                $this->right->addNode($node);
            }
        }
    }
    
    public function getSubtree($key) {
        if ($this->key == $key) {
            return $this;
        } else {
            for($i = 1 ; $i<20 ; $i++){
                $leftmost = 0;
                $rightmost= 0;
                $leftmost =bcpow(2, $i)*$this->key;
                $rightmost =(bcpow(2, $i)*$this->key) + (bcpow(2, $i) -1);
                if($key >= $leftmost AND $key <= $rightmost) {
                    break;
                }
            }
            $leftOrright = ($leftmost + $rightmost)/2;
            if($key < $leftOrright){
               return $this->left->getSubtree($key);
            }
            if($key > $leftOrright){
               return $this->right->getSubtree($key);
            }
        }
    }
    
    public function getNode($key) {
        if ($this->key == $key) {
            //$id = $this->data->getId();
            return $this->data;
        } else {
            for($i = 1 ; $i<20 ; $i++){
                $leftmost = 0;
                $rightmost= 0;
                $leftmost =bcpow(2, $i)*$this->key;
                $rightmost =(bcpow(2, $i)*$this->key) + (bcpow(2, $i) -1);
                if($key >= $leftmost AND $key <= $rightmost) {
                    break;
                }
            }
            $leftOrright = ($leftmost + $rightmost)/2;
            if($key < $leftOrright){
                return $this->left->getNode($key);
            }
            if($key > $leftOrright){
                return $this->right->getNode($key);
            }
        }
    }    
    
}