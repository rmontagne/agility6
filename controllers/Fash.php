<?php

class Fash {
     
    public function Home() {
        return Template::render('pages/home/fash_home', array(
            
        ));
    
    }
    
    public function Product() {
        return Template::render('pages/product/fash_product', array(
            
        ));
        
    }
    
}