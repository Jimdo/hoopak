<?php

/**
 * 
 **/
class ZipkinTracer
{
    // TODO properly import thrift constants
    private _endAnnotations = array("cr", "ss");
    
    public function __construct($scribeClient, $category="zipkin")
    {
        $this->_scribe = $scribeClient;
        $this->_category = $category;
        $this->_annotationsForTrace = array();
    }

    public function sendTrace($trace, $annotations)
    {

    }

    public function record($trace, $annotation)
    {
        $traceKey = array($trace->traceId, $trace->spanId);
        $this->_annotationsForTrace[$traceKey][] = $annotation;

        if in_array($annotation->name, $this->_endAnnotations) {
            $annotations = $this->_annotationsForTrace[$traceKey];
            unset($this->_annotationsForTrace[$traceKey]);
            $this->sendTrace($trace, $annotations);
        }
    }
}
