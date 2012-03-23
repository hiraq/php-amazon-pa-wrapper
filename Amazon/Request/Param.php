<?php

namespace AmazonProductAdvertising\Amazon\Request;

use AmazonProductAdvertising\Amazon as Amazon;
use AmazonProductAdvertising\Amazon\Exception as Amazon_Exception;

final class Param {
    
    static private $_obj = null;
    
    private $_params;
    
    private function __construct() {}
    
    static public function getInstance() {
        
        if( is_null(self::$_obj) ) {
            self::$_obj = new Param();
        }
        
        return self::$_obj;
        
    }
    
    public function setParams( $params ) {
        
        if( is_array($params) && !empty($params) ) {
            
            if( !empty($this->_params) && is_array($this->_params) ) {
                $this->_params = array_merge($this->_params,$params);
            }else{
                $this->_params = $params;
            }
            
        }
        
    }
    
    public function getParams() {
        return $this->_params;
    }        
    
}