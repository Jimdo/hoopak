<?php

namespace Hoopak;

use Zipkin\LogEntry;

$GLOBALS["THRIFT_ROOT"] = "/usr/lib/php";
require_once dirname(__FILE__) . "/Thrift/scribe/scribe_types.php";
require_once dirname(__FILE__) . "/Thrift/scribe/scribe.php";
require_once $GLOBALS["THRIFT_ROOT"].'/protocol/TBinaryProtocol.php';
require_once $GLOBALS["THRIFT_ROOT"].'/transport/TSocket.php';
require_once $GLOBALS["THRIFT_ROOT"].'/transport/TFramedTransport.php';

/**
 * A simple client for Scribe,
 * used as the transport to the Zipkin collector. 
 **/
class ScribeClient
{
    
    function __construct($host, $port)
    {
        $this->_host = $host;
        $this->_port = $port;
    }

    public function log($category, $message)
    {
        $logEntry = new \Zipkin\LogEntry(
            array(
                "category" => $category,
                "message" => $message
            )
        );
        $socket = new \TSocket($this->_host, $this->_port);
        $transport = new \TFramedTransport($socket);
        $protocol = new \TBinaryProtocol($transport);
        $scribe = new \Zipkin\scribeClient($protocol);
        $transport->open();
        $scribe->Log(array($logEntry));
        $transport->close();
    }
}
