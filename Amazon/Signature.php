<?php

namespace AmazonProductAdvertising\Amazon;

/*
 * import and aliasing for :
 * - Amazon
 * - Exception
 * - Param
 */
use AmazonProductAdvertising\Amazon as Amazon;
use AmazonProductAdvertising\Amazon\Exception as Amazon_Exception;
use AmazonProductAdvertising\Amazon\Request\Param as Amazon_Request_Param;

/**
 * @author Hiraq
 * @link https://github.com/hiraq/php-amazon-pa-wrapper
 * @package AmazonProductAdvertising
 * @subpackage Amazon
 * @name Signature
 * @version 1.0
 * @final 
 */
final class Signature {
    
    /**
     *
     * Amazon_Signature object
     * @staticvar object|null
     */
    static private $_obj = null;
    
    /**
     *
     * Amazon object
     * @var object
     */
    private $_amazon;
    
    /**
     *
     * Amazon_Request_Param object
     * @var object
     */
    private $_param;
    
    /**
     * Denied direct object instantiation
     * @access private 
     * @return void
     */
    private function __construct() {}
    
    /**
     *
     * Create object instance
     * 
     * @access public
     * @return object
     * @static
     */
    static public function getInstance() {
        
        if( is_null(self::$_obj) ) {
            self::$_obj = new Signature();
        }
        
        return self::$_obj;
        
    }
    
    /**
     *
     * Initialize signature
     * 
     * @access public
     * @param Amazon $amazon
     * @param Amazon_Request_Param $param
     * @throws Amazon_Exception 
     */
    public function init(Amazon $amazon,Amazon_Request_Param $param) {
        
        if( $amazon instanceof Amazon && $param instanceof Amazon_Request_Param ) {            
            $this->_amazon = $amazon;
            $this->_param = $param;
        }else{
            throw new Amazon_Exception('You must Amazon & Amazon_Request_Param object to build a query.');
        }
        
    }
    
    /**
     *
     * Create url signature
     * 
     * @link http://mierendo.com/software/aws_signed_query/
     * @access public
     * @param array $params
     * @return string
     * @throws Amazon_Exception 
     */
    public function create( $params ) {      
        
        /*
         * manage all parameters
         */
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
            
            /*
                Copyright (c) 2009 Ulrich Mierendorff

                Permission is hereby granted, free of charge, to any person obtaining a
                copy of this software and associated documentation files (the "Software"),
                to deal in the Software without restriction, including without limitation
                the rights to use, copy, modify, merge, publish, distribute, sublicense,
                and/or sell copies of the Software, and to permit persons to whom the
                Software is furnished to do so, subject to the following conditions:

                The above copyright notice and this permission notice shall be included in
                all copies or substantial portions of the Software.

                THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
                IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
                FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL
                THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
                LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
                FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER
                DEALINGS IN THE SOFTWARE.
            */
            
            /*
             * create query
             */
            $canonicalized_query = array();
            foreach ($params as $param => $value)
            {
                $param = str_replace("%7E", "~", rawurlencode($param));
                $value = str_replace("%7E", "~", rawurlencode($value));
                $canonicalized_query[] = $param."=".$value;
            }
            $canonicalized_query = implode("&", $canonicalized_query);
            $string_to_sign = $method."\n".$host."\n".$uri."\n".$canonicalized_query;
            
            //create signature
            $signature = base64_encode(hash_hmac("sha256", $string_to_sign, $private_key, true));
            $signature = str_replace("%7E", "~", rawurlencode($signature));
            
            //create url request
            $request = $parse_url['scheme'].'://'.$host.$uri.'?'.$canonicalized_query.'&Signature='.$signature;
            return $request;
            
        }else{
            throw new Amazon_Exception('Your endpoint url is not valid.');
        }                
        
    }
    
}