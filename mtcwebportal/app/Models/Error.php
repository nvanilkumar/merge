<?php

namespace App\Models;

class Error implements \JsonSerializable
{
    private $message;
    private $code;

    public function __construct($message, $code = 0)
    {
        $this->message = $message;
        $this->code = $code;
    }

    public function getMessage() 
    {
        return $this->message;
    }

    public function getCode() 
    {
        return $this->code;
    }

    public function jsonSerialize() 
    {
        $object = new \stdClass();
        $object->message = $this->message;
        $object->code = $this->code;

        return $object;
    }
}