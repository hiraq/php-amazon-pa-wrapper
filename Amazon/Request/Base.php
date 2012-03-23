<?php

namespace AmazonProductAdvertising\Amazon\Request;
use AmazonProductAdvertising\Amazon\Signature as Amazon_Signature;

abstract class Base {
    
    protected $_data;
    protected $_signature;
    
    public function setData( $data ) {
        
        $data = trim($data);
        if( !empty($data) ) {
            $this->_data = $data;
        }
        
    }
    
    public function setSignature(Amazon_Signature $signature) {
        $this->_signature = $signature;
    }
    
}

