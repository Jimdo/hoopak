<?php

/**
 * 
 **/
class ZipkinTracer
{
    
    public function __construct($scribeClient, $category="zipkin")
    {
        $this->_scribe = $scribeClient;
        $this->_category = $category;
    }

    public function sendTrace($trace, $annotation)
    {

    }
}
