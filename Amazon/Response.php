<?php

namespace AmazonProductAdvertising\Amazon;

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
    
    static public function getInstance() {
        
        if( is_null(self::$_obj) ) {
            self::$_obj = new Response();
        }
        
        return self::$_obj;
        
    }
    
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
            
        }
        
    }
    
}