<?php

namespace Hoopak;

/**
 * 
 **/
class ZipkinTracer
{
    public function __construct($scribeClient, $category="zipkin")
    {
        $this->_scribe = $scribeClient;
        $this->_category = $category;
        $this->_annotationsForTrace = array();
        // TODO properly import thrift constants
        $this->_endAnnotations = array("cr", "ss");
    }

    public function sendTrace($trace, $annotations)
    {
        $thriftOut = $this->thriftToBase64($trace, $annotations);
        $this->_scribe->log($this->category, $thriftOut);
    }

    public function record($trace, $annotation)
    {
        $traceKey = $trace->traceId . ":" . $trace->spanId;
        $this->_annotationsForTrace[$traceKey][] = $annotation;

        if (in_array($annotation->name, $this->_endAnnotations)) {
            $annotations = $this->_annotationsForTrace[$traceKey];
            unset($this->_annotationsForTrace[$traceKey]);
            $this->sendTrace($trace, $annotations);
        }
    }

    private function thriftToBase64($trace, $annotations)
    {
        $thriftAnnotations = array();
        $binaryAnnotations = array();

        foreach ($annotations as $annotation) {
            $host = null;
            if ($annotation->endpoint) {
                $host = new Endpoint(
                    array(
                        "ipv4" => $annotation->ipv4,
                        "port" => $annotation->port,
                        "service_name" => $annotation->port,
                    )
                );
            }

            if ($annotation->annotationType == 'timestamp') {
                $thriftAnnotations[] = new Annotation(
                    array(
                        "timestamp" => $annotation->value,
                        "value" => $annotation->name,
                        "host" => $host
                    )
                );
            } else {
                //TODO handle binary annotations
            }
        }

        $thriftTrace = new Span(
            array(
                "trace_id" => $trace->traceId,
                "name" => $trace->name,
                "id" => $trace->spanId,
                "parent_id" => $trace->parentId,
                "annotations" => $thriftAnnotations,
                "binary_annotations" => $binaryAnnotations
            )
        );

        $trans = new TMemoryBuffer();
        $proto = new TBinaryProtocol(trans);

        $thriftTrace->write($proto);

        return base64_encode($trans->getBuffer());
    }
}
