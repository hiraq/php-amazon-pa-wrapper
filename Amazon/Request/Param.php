<?php

namespace AmazonProductAdvertising\Amazon\Request;

/*
 * import and aliasing for :
 * - Amazon
 * - Exception
 */
use AmazonProductAdvertising\Amazon as Amazon;
use AmazonProductAdvertising\Amazon\Exception as Amazon_Exception;

/**
 * @author Hiraq
 * @link https://github.com/hiraq/php-amazon-pa-wrapper
 * @package AmazonProductAdvertising/Amazon
 * @subpackage Request  
 * @name Param
 * @version 1.0
 * @final
 */
final class Param {
    
    /**
     *
     * Amazon_Request_Param
     * @staticvar null|object
     */
    static private $_obj = null;
    
    /**
     *
     * Parameters request
     * @var array
     */
    private $_params;
    
    /**
     * Denied direct object instantiation
     * @access private
     * @return void 
     */
    private function __construct() {}
    
    /**
     *
     * Create object instantiation
     * 
     * @access public
     * @return object
     * @static
     */
    static public function getInstance() {
        
        if( is_null(self::$_obj) ) {
            self::$_obj = new Param();
        }
        
        return self::$_obj;
        
    }
    
    /**
     *
     * Set parameters operation. Merging if any params exists.
     * 
     * @access public
     * @param array $params 
     * @return void
     */
    public function setParams( $params ) {
        
        if( is_array($params) && !empty($params) ) {
            
            if( !empty($this->_params) && is_array($this->_params) ) {
                $this->_params = array_merge($this->_params,$params);
            }else{
                $this->_params = $params;
            }
            
        }
        
    }
    
    /**
     *
     * Get parameters
     * 
     * @access public
     * @return array
     */
    public function getParams() {
        return $this->_params;
    }        
    
}