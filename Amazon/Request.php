<?php

namespace AmazonProductAdvertising\Amazon;

use AmazonProductAdvertising\Amazon\Signature as Amazon_Signature;
use AmazonProductAdvertising\Amazon\Request\Search as Amazon_Request_Search;
use AmazonProductAdvertising\Amazon\Request\Lookup as Amazon_Request_Lookup;

final class Request {
    
    static private $_obj = null;
    
    private function __construct() {}
    
    static public function getInstance() {
        
    }
    
}