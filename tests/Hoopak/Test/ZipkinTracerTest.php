<?php

namespace Hoopak\Test;

use Hoopak\Annotation;
use Hoopak\Endpoint;
use Hoopak\Trace;
use Hoopak\ScribeClient;
use Hoopak\ZipkinTracer;

class ZipkinTracerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function aTraceIsBase64EncodedWhenSendingItToScribe()
    {
        $scribe = new ScribeClient("localhost", 9410);
        $zipkinTracer = new ZipkinTracer($scribe);
        $trace = new Trace("geloet", null, null, null, array($zipkinTracer));
        $trace->setEndpoint(new Endpoint("1.2.3.4", "8000", "a-client"));
        $trace->record(Annotation::string("spam", "eggs"));
        $trace->record(Annotation::clientSend());
        usleep(3000);
        $trace1 = $trace->child("heavy-lifting");
        $trace1->setEndpoint(new Endpoint("10.1.2.3", "80", "the-service"));
        $trace1->record(Annotation::serverReceive());
        usleep(1000);
        $trace1->record(Annotation::serverSend());
        usleep(500);
        $trace->record(Annotation::clientReceive());
        //print_r($scribe->messages);
        //$this->assertEquals("foo", $scribe->messages);
    }
}

class ScribeMock
{
    public $messages = array();

    public function log($category, $message)
    {
        $this->messages[] = $message;
    }

}
