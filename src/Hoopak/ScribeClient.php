<?php

namespace Hoopak;

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
        $logEntry = new \Scribe\LogEntry(
            array(
                "category" => $category,
                "message" => $message
            )
        );
        $socket = new \Thrift\Transport\TSocket($this->_host, $this->_port);
        $transport = new \Thrift\Transport\TFramedTransport($socket);
        $protocol = new \Thrift\Protocol\TBinaryProtocol($transport);
        $scribe = new \Zipkin\scribeClient($protocol);
        $transport->open();
        $scribe->Log(array($logEntry));
        $transport->close();
    }
}
