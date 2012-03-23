<?php

namespace AmazonProductAdvertising\Amazon;

/*
 * import and aliasing for :
 * - Amazon
 * - Signature
 * - Search
 * - Lookup
 * - Param
 */
use AmazonProductAdvertising\Amazon as Amazon;
use AmazonProductAdvertising\Amazon\Signature as Amazon_Signature;
use AmazonProductAdvertising\Amazon\Exception as Amazon_Exception;
use AmazonProductAdvertising\Amazon\Request\Search as Amazon_Request_Search;
use AmazonProductAdvertising\Amazon\Request\Lookup as Amazon_Request_Lookup;
use AmazonProductAdvertising\Amazon\Request\Param as Amazon_Request_Param;

/**
 * @author Hiraq
 * @link https://github.com/hiraq/php-amazon-pa-wrapper
 * @package AmazonProductAdvertising
 * @subpackage Amazon
 * @name Request
 * @version 1.0
 * @final 
 */
final class Request {
    
    /**
     *
     * Amazon_Request object
     * @staticvar null|object
     */
    static private $_obj = null;
    
    /**
     *
     * Amazon object
     * @var object
     */
    private $_amazon = null;
    
    /**
     *
     * Operation to proceed
     * @var string
     */
    private $_operation;  
    
    /**
     *
     * A data if operation is search
     * @var string
     */
    private $_keyword;
    
    /**
     *
     * A data if operation is lookup
     * @var string
     */
    private $_asin;
    
    /**
     *
     * Amazon_Request_Param object
     * @var object
     */
    private $_params;        
    
    /**
     * Denied object instantiation
     * @access private
     * @return void 
     */
    private function __construct() {}
    
    /**
     *
     * Create Amazon_Request object
     * 
     * @access public
     * @return object
     * @static
     */
    static public function getInstance() {
        
        if( is_null(self::$_obj) ) {
            self::$_obj = new Request();
        }
        
        return self::$_obj;
        
    }
    
    /**
     *
     * Set Amazon object
     * 
     * @access public
     * @param Amazon $obj
     * @throws Amazon_Exception 
     * @return void
     */
    public function setAmazon(Amazon $obj) {
        
        if( $obj instanceof Amazon) {
            $this->_amazon = $obj;
        }else{
            throw new Amazon_Exception('Given object must be an instance of Amazon class.');
        }
        
    }
    
    /**
     *
     * Set parameters and Amazon_Request_Param object
     * 
     * @access public
     * @param array $params 
     * @return void
     */
    public function setParams( $params ) {
        
        $param = Amazon_Request_Param::getInstance();
        $param->setParams($params);
        
        $this->_params = $param;
        
    }
    
    /**
     * Search item in amazon
     * 
     * @access public
     * @param string $keyword 
     */
    public function search( $keyword) {
        
        $keyword = trim($keyword);
        
        if( !empty($keyword) ) {
            $this->_operation = 'search';
            $this->_keyword = $keyword;            
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
    
    /**
     *
     * Send request to Amazon server
     * 
     * @access public
     * @throws Amazon_Exception      
     */
    public function send() {
        
        if( empty($this->_operation) ) {
            throw new Amazon_Exception('You must set operation first before send request.');
        }else{
            
            $className = 'Amazon_Request_'.ucfirst($this->_operation);
            switch( $className ) {
                
                case 'Amazon_Request_Search':
                    $data = $this->_keyword;
                    $classObj = Amazon_Request_Search::getInstance();                    
                    break;
                
                case 'Amazon_Request_Lookup':
                    $data = $this->_asin;
                    $classObj = Amazon_Request_Lookup::getInstance();
                    break;
                
            }
            
            $signature = Amazon_Signature::getInstance();                        
            $signature->init($this->_amazon,$this->_params);
            
            $classObj->setData($data);
            $classObj->setSignature($signature);
            
            $url = $classObj->build();
            return $url;
        }
        
    }        
    
}