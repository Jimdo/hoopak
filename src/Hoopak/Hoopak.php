<?php

namespace Hoopak;

class Hoopak
{
    private static $tracers = array();

    private function __construct() {}
    private function __clone() {}

    public static function setTracers($tracers)
    {
        self::$tracers = $tracers;
    }

    public static function pushTracer($tracer)
    {
        self::$tracers[] = $tracer;
    }

    public static function getTracers()
    {
        return self::$tracers;
    }
}
