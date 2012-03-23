<?php

namespace AmazonProductAdvertising\Amazon\Request;
use AmazonProductAdvertising\Amazon\Request\Base as Amazon_Request_Base;

final class Search extends Amazon_Request_Base {
    
    static private $_obj = null;            
    
    private function __construct() {}
    
    static public function getInstance() {
        
        if( is_null(self::$_obj) ) {
            self::$_obj = new Search();
        }
        
        return self::$_obj;
        
    }                        
    
    public function build() {                
        
        return $this->_signature->create(array(
            'Keywords' => $this->_data,
            'Operation' => 'ItemSearch'
        ));
        
    }
    
}