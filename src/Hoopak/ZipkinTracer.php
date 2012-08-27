<?php
namespace Hoopak;

$GLOBALS["THRIFT_ROOT"] = "/usr/lib/php";
require_once dirname(__FILE__) . "/Thrift/zipkinCore/zipkinCore_types.php";
require_once dirname(__FILE__) . "/Thrift/zipkinCore/zipkinCore_constants.php";
require_once $GLOBALS["THRIFT_ROOT"].'/protocol/TBinaryProtocol.php';
require_once $GLOBALS["THRIFT_ROOT"].'/transport/TMemoryBuffer.php';



use Zipkin\Annotation;
use Zipkin\Span;
use Zipkin\Endpoint;
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
        $this->_scribe->log($this->_category, $thriftOut);
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
                $host = new \Zipkin\Endpoint(
                    array(
                        "ipv4" => ip2long($annotation->endpoint->ipv4),
                        "port" => $annotation->endpoint->port,
                        "service_name" => $annotation->endpoint->serviceName,
                    )
                );
            }

            if ($annotation->annotationType == 'timestamp') {
                $thriftAnnotations[] = new \Zipkin\Annotation(
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

        $thriftTrace = new \Zipkin\Span(
            array(
                "trace_id" => $trace->traceId,
                "name" => $trace->name,
                "id" => $trace->spanId,
                "parent_id" => $trace->parentSpanId,
                "annotations" => $thriftAnnotations,
                "binary_annotations" => $binaryAnnotations
            )
        );

        $trans = new \TMemoryBuffer();
        $proto = new \TBinaryProtocol($trans);

        $thriftTrace->write($proto);

        return base64_encode($trans->getBuffer());
    }
}
