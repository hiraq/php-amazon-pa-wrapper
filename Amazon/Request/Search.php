<?php

namespace AmazonProductAdvertising\Amazon\Request;

use AmazonProductAdvertising\Amazon\Signature as Amazon_Signature;
use AmazonProductAdvertising\Amazon\Exception as Amazon_Exception;
use AmazonProductAdvertising\Amazon\Request\Param as Amazon_Request_Param;

final class Search {
    
    static private $_obj = null;
    
    private $_signature;
    private $_amazon;
    private $_data;        
    private $_param;
    
    private function __construct() {}
    
    static public function getInstance() {
        
        if( is_null(self::$_obj) ) {
            self::$_obj = new Search();
        }
        
        return self::$_obj;
        
    }                
    
    public function setData( $data ) {
        
        $data = trim($data);
        if( !empty($data) ) {
            $this->_data = $data;
        }
        
    }
    
    public function setSignature(Amazon_Signature $signature) {
        $this->_signature = $signature;
    }
    
    public function build() {                
        
        return $this->_signature->create(array(
            'Keywords' => $this->_data,
            'Operation' => 'ItemSearch'
        ));
        
    }
    
}