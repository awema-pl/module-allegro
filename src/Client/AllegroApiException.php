<?php

namespace AwemaPL\Allegro\Client;
use Exception;

class AllegroApiException extends Exception
{
    protected $errorCode;

    public function __construct($message = '', $errorCode = '', Throwable $previous = null)
    {
        parent::__construct($message, 0, $previous);
        $this->errorCode = $errorCode;
    }

    public function getErrorCode()
    {
        return $this->errorCode;
    }
}