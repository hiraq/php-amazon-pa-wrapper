<?php

namespace AmazonProductAdvertising\Amazon;

use AmazonProductAdvertising\Amazon as Amazon;
use AmazonProductAdvertising\Amazon\Signature as Amazon_Signature;
use AmazonProductAdvertising\Amazon\Exception as Amazon_Exception;
use AmazonProductAdvertising\Amazon\Request\Search as Amazon_Request_Search;
use AmazonProductAdvertising\Amazon\Request\Lookup as Amazon_Request_Lookup;

final class Request {
    
    static private $_obj = null;
    
    private $_amazon = null;
    
    private $_operation;
    private $_search_index;
    private $_keyword;
    private $_asin;
    
    private function __construct() {}
    
    static public function getInstance() {
        
        if( is_null(self::$_obj) ) {
            self::$_obj = new Request();
        }
        
        return self::$_obj;
        
    }
    
    public function setAmazon(Amazon $obj) {
        
        if( $obj instanceof Amazon) {
            $this->_amazon = $obj;
        }else{
            throw new Amazon_Exception('Given object must be an instance of Amazon class.');
        }
        
    }
    
    /**
     * Search item in amazon
     * 
     * @access public
     * @param string $keyword 
     */
    public function search( $keyword, $search_index ) {
        
        $keyword = trim($keyword);
        
        if( !empty($keyword) ) {
            $this->_operation = 'search';
            $this->_keyword = $keyword;
            $this->_search_index = $search_index;
        }
        
    }
    
    /**
     *
     * Get detail information about given item asin id
     * 
     * @access public
     * @param string $asin 
     */
    public function detail( $asin ) {
        
        $asin = trim($asin);
        
        if( !empty($asin) ) {
            $this->_operation = 'lookup';
            $this->_asin = $asin;
        }
        
    }
    
    public function send() {
        
        if( empty($this->_operation) ) {
            throw new Amazon_Exception('You must set operation first before send request.');
        }else{
            
            $className = 'Amazon_Request_'.ucfirst($this->_operation);
            switch( $className ) {
                
                case 'Amazon_Request_Search':
                    $data = $this->_keyword;
                    $classObj = Amazon_Request_Search::getInstance();
                    $classObj->setIndex($this->_search_index);
                    break;
                
                case 'Amazon_Request_Lookup':
                    $data = $this->_asin;
                    $classObj = Amazon_Request_Lookup::getInstance();
                    break;
                
            }
            
            $signature = Amazon_Signature::getInstance();
                        
            $classObj->createSignature($signature,$this->_amazon);
            $classObj->setData($data);
            
            $url = $classObj->build();
            debug($url);
        }
        
    }
    
}