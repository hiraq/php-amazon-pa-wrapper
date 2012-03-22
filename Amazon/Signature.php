<?php

namespace AmazonProductAdvertising\Amazon;

final class Signature {
    
    static private $_obj = null;
    
    private function __construct() {}
    
    static public function getInstance() {
        
        if( is_null(self::$_obj) ) {
            self::$_obj = new Signature();
        }
        
        return self::$_obj;
        
    }
    
    public function create( $params ) {
        
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
            echo $request;
            
        }                
        
    }
    
}