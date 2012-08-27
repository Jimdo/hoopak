<?php

namespace Hoopak;

class Endpoint
{
    
    public function __construct($ipv4, $port, $serviceName)
    {
        $this->ipv4 = $ipv4;
        $this->port = $port;
        $this->serviceName = $serviceName;
    }
}
