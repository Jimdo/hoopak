<?php

namespace Hoopak\Test;

use Hoopak\Trace;
use Hoopak\Hoopak;

class TraceTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function aTraceCanBeCreated()
    {
        $trace = new Trace("a fresh trace");
        $this->assertNotNull($trace->traceId);
    }

    /**
     * @test
     */
    public function tracersAreLookedUpGloballyIfNoneAreConfigured()
    {
        $tracer = new \stdClass();
        Hoopak::pushTracer($tracer);
        $trace = new Trace("trace");
        $expected = array($tracer);
        $this->assertEquals($expected, $trace->_tracers);
    }
    
}
