<?php

namespace AmazonProductAdvertising\Amazon\Request;
use AmazonProductAdvertising\Amazon\Signature as Amazon_Signature;

/**
 * @author Hiraq
 * @link https://github.com/hiraq/php-amazon-pa-wrapper
 * @package AmazonProductAdvertising/Amazon
 * @subpackage Request  
 * @name Base
 * @version 1.0
 * @abstract
 */
abstract class Base {
    
    /**
     *
     * Important data such as 'Keywords'
     * @var string
     */
    protected $_data;
    
    /**
     *
     * Amazon_Signature object
     * @var Amazon_Signature
     */
    protected $_signature;
    
    /**
     *
     * Set data
     * 
     * @access public
     * @param string $data 
     * @return void
     */
    public function setData( $data ) {
        
        $data = trim($data);
        if( !empty($data) ) {
            $this->_data = $data;
        }
        
    }
    
    /**
     *
     * Set Amazon_Signature object
     * 
     * @access public
     * @param Amazon_Signature $signature 
     * @return void
     */
    public function setSignature(Amazon_Signature $signature) {
        $this->_signature = $signature;
    }
    
}

