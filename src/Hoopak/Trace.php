<?php
namespace Hoopak;


function _id()
{
    return rand(0, pow(2,63) - 1);
}

class Trace
{
    
    /**
     * Create a Trace.
     */
    public function __construct($name, $traceId=null, $spanId=null, $parentSpanId=null, $tracers=array())
    {
        $this->name = $name;

        if ($traceId) {
            $this->traceId = $traceId;
        } else {
            $traceId = _id();
        }

        if ($spanId) {
            $this->spanId = $spanId;
        } else {
            $spanId = _id();
        }

        $this->parentSpanId = $parentSpanId;

        $this->_tracers = $tracers;

        $this->_endpoint = null;
    }

    /**
     * Record an annotation
     */ 
    public function record($annotation)
    {
        if (!$annotation->endpoint && $this->_endpoint) {
            $annotation->endpoint = $this->endpoint;
        }

        foreach ($this->_tracers as $tracer) {
            $tracer->record($annotation);
        }

    }

    /**
     * Create a child of this trace
     */
    public function child($name)
    {
        $trace = new self::_construct($name, $this->traceId, null, $this->spanId);
        $trace->setEndpoint($this->_endpoint);
        return $trace;
    }

    /**
     * Set the endpoint
     */
    public function setEndpoint($endpoint)
    {
        $this->_endpoint = $endpoint
    }

}
