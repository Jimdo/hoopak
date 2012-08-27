<?php

namespace Hoopak\Test;

use Hoopak\Annotation;
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
        $trace = new Trace("foo", 1, 1, 1, array($zipkinTracer));
        $trace->record(Annotation::clientReceive(1234455632));
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
