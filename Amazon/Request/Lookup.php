<?php

namespace AmazonProductAdvertising\Amazon\Request;

/*
 * import and aliasing data for :
 * - Base
 */
use AmazonProductAdvertising\Amazon\Request\Base as Amazon_Request_Base;

/**
 * @author Hiraq
 * @link https://github.com/hiraq/php-amazon-pa-wrapper
 * @package AmazonProductAdvertising/Amazon
 * @subpackage Request  
 * @name Lookup
 * @version 1.0
 * @license BSD-3-Clause(http://www.opensource.org/licenses/BSD-3-Clause)
 * @final
 */
final class Lookup extends Amazon_Request_Base {
    
    /**
     *
     * Search Object
     * @staticvar null|object
     */
    static private $_obj = null;            
    
    /**
     * Denied direct object instantiation
     * 
     * @access private
     * @return void 
     */
    private function __construct() {}
    
    /**
     *
     * Create Lookup object
     * 
     * @access public
     * @return Search
     * @static
     */
    static public function getInstance() {
        
        if( is_null(self::$_obj) ) {
            self::$_obj = new Lookup();
        }
        
        return self::$_obj;
        
    }                        
    
    /**
     *
     * Get signatured request
     * 
     * @access public
     * @uses Amazon_Signature
     * @return string     
     */
    public function build() {                
        
        return $this->_signature->create(array(
            'ItemId' => $this->_data,
            'Operation' => 'ItemLookup'
        ));
        
    }
    
}