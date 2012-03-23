<?php

namespace AmazonProductAdvertising\Amazon\Request;

use AmazonProductAdvertising\Amazon as Amazon;
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
    
    public function createSignature(Amazon_Signature $obj,Amazon $amazon,Amazon_Request_Param $param) {
        
        if( $obj instanceof Amazon_Signature && $amazon instanceof Amazon && $param instanceof Amazon_Request_Param) {
            $this->_signature = $obj;
            $this->_amazon = $amazon;
            $this->_param = $param;
        }else{
            throw new Amazon_Exception('You must Amazon_Signature and Amazon object to build a query.');
        }
        
    }        
    
    public function setData( $data ) {
        
        $data = trim($data);
        if( !empty($data) ) {
            $this->_data = $data;
        }
        
    }
    
    public function build() {
        
        $request_params = array_merge(array(
            'AWSAccessKeyId' => $this->_amazon->getAwsKey(),            
            'AssociateTag' => $this->_amazon->getTagKey(),
            'Service' => 'AWSECommerceService',
            'Operation' => 'ItemSearch',
            'Timestamp' => gmdate("Y-m-d\TH:i:s\Z"), 
            'Keywords' => $this->_data,            
            'Version' => Amazon::VERSION_AMAZON,
            'endpoint' => $this->_amazon->getEndPoint(),
            'secret_key' => $this->_amazon->getSecretKey()
        ),$this->_param->getParams());
        
        return $this->_signature->create($request_params);
        
    }
    
}