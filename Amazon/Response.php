<?php

namespace AmazonProductAdvertising\Amazon;

/*
 * import and aliasing for :
 * - Exception
 */
use AmazonProductAdvertising\Amazon\Exception as Amazon_Exception;

/**
 * @author Hiraq
 * @link https://github.com/hiraq/php-amazon-pa-wrapper
 * @package AmazonProductAdvertising
 * @subpackage Amazon
 * @name Response
 * @version 1.0
 * @final 
 */
final class Response {
    
    /**
     *
     * Response object
     * @staticvar null|object
     */
    static private $_obj = null;
    
    private $_url;
    private $_raw;
    
    /**
     * Denied direct object instantiation
     * 
     * @access private
     * @return void 
     */
    private function __construct() {}
    
    /**
     *
     * Create Response object
     * 
     * @access public
     * @return object
     * @static
     */
    static public function getInstance() {
        
        if( is_null(self::$_obj) ) {
            self::$_obj = new Response();
        }
        
        return self::$_obj;
        
    }
    
    /**
     *
     * Proceed request to get response data
     * 
     * @access public
     * @param string $url
     * @throws Amazon_Exception 
     */
    public function proceed( $url ) {
        
        if( filter_var($url,FILTER_VALIDATE_URL) ) {
                        
            $contents = @file_get_contents($url);
            $xml = new \SimpleXMLElement($contents);
            
            $this->_url = $url;
            $this->_raw = $xml;                        
            
        }else{
            throw new Amazon_Exception('Requested url must be a valid url.');
        }
        
    }
    
    /**
     *
     * Get raw xml content data
     * 
     * @access public
     * @return SimpleXMLElement object
     */
    public function getRawXml() {
        return $this->_raw;
    }
    
    /**
     *
     * Get total results
     * 
     * @access public
     * @return int
     */
    public function getTotalResults() {
        return (int) $this->_raw->Items->TotalResults - 1;
    }
    
    /**
     *
     * Get total pages
     * 
     * @access public
     * @return int
     */
    public function getTotalPages() {
        return (int) $this->_raw->Items->TotalPages - 1;
    }
    
    /**
     *
     * Get more search results
     * 
     * @access public
     * @return string
     */
    public function getMoreSearchResults() {
        return (string) $this->_raw->Items->MoreSearchResultsUrl;
    }
    
    /**
     *
     * Get operation headers data
     * 
     * @access public
     * @return array
     * @throws Amazon_Exception 
     */
    public function getOperationHeaders() {
        
        if( !empty($this->_raw) && $this->_raw instanceof \SimpleXMLElement ) {
                        
            $headers = array();
            
            $arguments = function($args){
                
                $return = array();
                foreach($args->Argument as $arg_key) {
                    
                    foreach($arg_key->attributes() as $key => $val) {
                        
                        if( 'Name' == $key ) {
                            $key_index = (string) $val;
                        }
                        
                        if( 'Value' == $key) {
                            $return[$key_index] = (string) $val;
                        }
                        
                    }
                    
                }
                
                return $return;
                
            };
            
            $headers['OperationRequest'] = array(
                'RequestId' => (string) $this->_raw->OperationRequest->RequestId,
                'RequestProcessingTime' => (string) $this->_raw->OperationRequest->RequestProcessingTime,
                'Arguments' => $arguments($this->_raw->OperationRequest->Arguments)
            );
            
            return $headers;
            
        }else{
            throw new Amazon_Exception('Returning response not a valid xml document.');
        }
        
    }
    
    /**
     *
     * Get all errors message type and content
     * 
     * @access public
     * @return array
     * @throws Amazon_Exception 
     */
    public function getErrors() {
        
        if( !empty($this->_raw) && $this->_raw instanceof \SimpleXMLElement) {
            
            $errors = array();            
            $arguments = function($args){
                
                $return = array();
                foreach($args->Error as $arg_key => $arg_val) {
                    
                    $key_index = '';
                    $val_value = '';
                    
                    foreach($arg_val as $key => $val) {
                        
                        if($key == 'Code') {
                            $key_index = (string) $val;
                        }
                        
                        $val_value = (string) $val;
                    }
                    
                    $return[$key_index] = $val_value;
                    
                }
                
                return $return;
                
            };
            
            $errors = array(
                'Errors' => $arguments($this->_raw->Items->Request->Errors)
            );
            
            return $errors;
            
        }else{
            throw new Amazon_Exception('Returning response not a valid xml document.');
        }
        
    }
    
    /**
     *
     * Check if request is valid or not
     * 
     * @access public
     * @return boolean
     * @throws Amazon_Exception 
     */
    public function isRequestValid() {
        
        if( !empty($this->_raw) && $this->_raw instanceof \SimpleXMLElement) {
            
            $check_valid = (string) $this->_raw->Items->Request->IsValid;
            return $check_valid == 'True' ? true : false;
            
        }else{
            throw new Amazon_Exception('Returning response not a valid xml document.');
        }
        
    }
    
}