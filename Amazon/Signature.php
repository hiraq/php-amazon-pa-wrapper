<?php

namespace AmazonProductAdvertising\Amazon;

use AmazonProductAdvertising\Amazon as Amazon;
use AmazonProductAdvertising\Amazon\Exception as Amazon_Exception;
use AmazonProductAdvertising\Amazon\Request\Param as Amazon_Request_Param;

final class Signature {
    
    static private $_obj = null;
    
    private $_amazon;
    private $_param;
    
    private function __construct() {}
    
    static public function getInstance() {
        
        if( is_null(self::$_obj) ) {
            self::$_obj = new Signature();
        }
        
        return self::$_obj;
        
    }
    
    public function init(Amazon $amazon,Amazon_Request_Param $param) {
        
        if( $amazon instanceof Amazon && $param instanceof Amazon_Request_Param ) {            
            $this->_amazon = $amazon;
            $this->_param = $param;
        }else{
            throw new Amazon_Exception('You must Amazon & Amazon_Request_Param object to build a query.');
        }
        
    }
    
    public function create( $params ) {      
        
        $this->_param->setParams(array(
            'AWSAccessKeyId' => $this->_amazon->getAwsKey(),            
            'AssociateTag' => $this->_amazon->getTagKey(),
            'Service' => 'AWSECommerceService',            
            'Timestamp' => gmdate("Y-m-d\TH:i:s\Z"),                 
            'Version' => Amazon::VERSION_AMAZON,
            'endpoint' => $this->_amazon->getEndPoint(),
            'secret_key' => $this->_amazon->getSecretKey()
        ));
        
        $params = array_merge($params,$this->_param->getParams());        
        
        $method = 'GET';
        $parse_url = parse_url($params['endpoint']);
        
        if( is_array($parse_url) && !empty($parse_url) ) {
         
            $host = $parse_url['host'];
            $uri = $parse_url['path'];
            $private_key = $params['secret_key'];
            
            unset($params['endpoint']);
            unset($params['secret_key']);
            
            ksort($params);
            
            $canonicalized_query = array();
            foreach ($params as $param => $value)
            {
                $param = str_replace("%7E", "~", rawurlencode($param));
                $value = str_replace("%7E", "~", rawurlencode($value));
                $canonicalized_query[] = $param."=".$value;
            }
            $canonicalized_query = implode("&", $canonicalized_query);
            $string_to_sign = $method."\n".$host."\n".$uri."\n".$canonicalized_query;
            
            $signature = base64_encode(hash_hmac("sha256", $string_to_sign, $private_key, true));
            $signature = str_replace("%7E", "~", rawurlencode($signature));
            
            $request = $parse_url['scheme'].'://'.$host.$uri.'?'.$canonicalized_query.'&Signature='.$signature;
            return $request;
            
        }else{
            throw new Amazon_Exception('Your endpoint url is not valid.');
        }                
        
    }
    
}