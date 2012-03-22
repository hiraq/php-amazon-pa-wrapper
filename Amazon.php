<?php

namespace Amazon {
    
    final class Amazon {
        
        /**
         * Amazon Product Advertising API version
         */
        const VERSION = '2011-08-01';
        
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
        private function __construct() {}
        
        /**
         * Denied clone object
         * @access private 
         */
        private function __clone() {}
        
        /**
         * Create object instantiation
         * 
         * @access public
         * @return object
         * @static 
         */
        static public function getInstance() {
            
            if( is_null(self::$_obj) ) {
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
        static public function checkCompat() {
            
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
        public function setAccountIds( $aws_key,$secret_key,$tag_key ) {
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
        public function setEndPoint( $endpoint ) {
            $this->_endpoint_url = $endpoint;
        }
        
        public function request() {
            
        }
        
    }
    
}
