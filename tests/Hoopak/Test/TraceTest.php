<?php

namespace Hoopak\Test;

use Hoopak\Trace;

class TraceTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function aTraceCanBeCreated()
    {
        $trace = new Trace();
        $this->assertNotNull($trace->traceId);
    }
    
}