<?php

namespace AmazonProductAdvertising;

/*
 * import and aliasing :
 * - Exception
 * - Request
 * - Response
 */
use AmazonProductAdvertising\Amazon\Exception as Amazon_Exception;
use AmazonProductAdvertising\Amazon\Request as Amazon_Request;
use AmazonProductAdvertising\Amazon\Response as Amazon_Response;

final class Amazon {
    /**
     * Amazon Product Advertising API version
     */

    const VERSION_AMAZON = '2011-08-01';

    /**
     * Library version 
     */
    const VERSION_LIB = '1.0';

    /**
     *
     * Store Amazon object class
     * @staticvar null|object
     */
    static private $_obj = null;

    /**
     *
     * AWS key
     * @var string 
     */
    private $_aws_key;

    /**
     *
     * AWS Secret Key
     * @var string
     */
    private $_secret_key;

    /**
     * Associate ID
     * @var string
     */
    private $_tag_key;

    /**
     *
     * Endpoint request url
     * @var string
     */
    private $_endpoint_url = 'http://webservices.amazon.com/onca/xml';

    /**
     * Denied direct instantiation
     * @access private          
     */
    private function __construct() {
        
    }

    /**
     * Denied clone object
     * @access private 
     */
    private function __clone() {
        
    }

    /**
     * Create object instantiation
     * 
     * @access public
     * @return object
     * @static 
     */
    static public function getInstance() {

        if (is_null(self::$_obj)) {
            self::$_obj = new Amazon();
        }

        return self::$_obj;
    }

    /**
     * Check php compatibility
     * 
     * @access public
     * @return boolean
     * @static 
     */
    static public function checkCompatHash() {

        if (!function_exists('hash_hmac')) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Set php autoload
     * 
     * @access public
     * @return void 
     * @static
     */
    static public function set_autoload() {
        spl_autoload_register(__NAMESPACE__ . '\Amazon::autoload');
    }

    /**
     *
     * Set autoload for all amazon libs
     * 
     * @access public
     * @param string $classname 
     * @return void
     * @static
     */
    static public function autoload($classname) {

        if (strstr($classname, '\\')) {

            $exp_name = explode('\\', $classname);
            $top_namespace = current($exp_name);
            $sub_namespace = next($exp_name);

            $exp_class = explode('_', end($exp_name));
            $class_namespace = end($exp_class);

            if ('AmazonProductAdvertising' == $top_namespace && 'Amazon' == $sub_namespace) {
                require_once $sub_namespace . '/' . $class_namespace . '.php';
            }
        }
    }

    /**
     *
     * Set Amazon Account ID
     * 
     * @access public
     * @param string $aws_key
     * @param string $secret_key
     * @param string $tag_key 
     * @return void
     */
    public function setAccountIds($aws_key, $secret_key, $tag_key) {
        $this->_aws_key = $aws_key;
        $this->_secret_key = $secret_key;
        $this->_tag_key = $tag_key;
    }

    /**
     *
     * Set new endpoint url
     * 
     * @param string $endpoint 
     * @return void
     */
    public function setEndPoint($endpoint) {
        $this->_endpoint_url = $endpoint;
    }

    public function request() {

        if (empty($this->_aws_key) || empty($this->_secret_key) || empty($this->_tag_key)) {
            throw new Amazon_Exception('You must set your amazon aws,secret key and your associate tag.');
        } else {
            
            
            
        }
    }

}
