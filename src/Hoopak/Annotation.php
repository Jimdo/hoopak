<?php

namespace Hoopak;

/**
 * Annotation.
 **/
class Annotation
{
    
    public function __construct($name, $value, $annotationType, $endpoint)
    {
        $this->name = $name;
        $this->value = $value;
        $this->annotationType = $annotationType;
        $this->endpoint = endpoint;
    }

    public static function timestamp($name, $timestamp=null)
    {
        if(!$timestamp) {
            $timestamp = round(microtime(true) * 1000);
        }

        return new self($name, $timestamp, "timestamp");
    }

    public static function clientSend($timestamp=null)
    {
        return self::timestamp("cs", $timestamp);
    }

    public static function clientReceive($timestamp=null)
    {
        return self::timestamp("cr", $timestamp);
    }

    public static function serverSend($timestamp=null)
    {
        return self::timestamp("ss", $timestamp);
    }

    public static function serverReceive($timestamp=null)
    {
        return self::timestamp("sr", $timestamp);
    }

    public static function string($name, $value)
    {
        return new self($name, $value, "string");
    }

    public static function bytes($name, $value)
    {
        return new self($name, $value, "bytes");
    }
}
