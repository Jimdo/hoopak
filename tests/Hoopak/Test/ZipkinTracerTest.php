<?php

namespace Hoopak\Test;

use Hoopak\Annotation;
use Hoopak\Endpoint;
use Hoopak\Trace;
use Hoopak\ZipkinTracer;

class ZipkinTracerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function aTraceIsBase64EncodedWhenSendingItToScribe()
    {
        $scribe = new ScribeMock();
        $zipkinTracer = new ZipkinTracer($scribe);
        $trace = new Trace("foo", null, null, null, array($zipkinTracer));
        $trace->setEndpoint(new Endpoint("1.2.3.4", "8000", "service"));
        $trace->record(Annotation::clientReceive());
        $this->assertEquals("foo", $scribe->message);
    }
}

class ScribeMock
{
    public $message;

    public function log($category, $message)
    {
        $this->message = $message;
    }

}
