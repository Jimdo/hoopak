<?php
namespace Hoopak;


static function _id()
{
    return rand(0, pow(2,63));
}

class Trace
{
    
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
}
