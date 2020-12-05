<?php

namespace AwemaPL\Allegro\Client;

use Exception;
use Throwable;

class AllegroRestApiException extends Exception implements Throwable
{
    /** @var string $responseMessage */
    private $responseMessage;

    /** @var string $responseCode */
    private $responseCode;

    /** @var object|null */
    private $response;

    public function __construct(string $responseMessage, string $responseCode,?object $response = null)
    {
        parent::__construct("[{$responseCode}] {$responseMessage}");
        $this->responseMessage = $responseMessage;
        $this->responseCode = $responseCode;
        $this->response = $response;
    }

    /**
     * Response message
     *
     * @return string
     */
    public function responseMessage(): string
    {
        return $this->responseMessage;
    }

    /**
     * Response code
     *
     * @return string
     */
    public function responseCode(): string
    {
        return $this->responseCode;
    }

    /**
     * Get response
     *
     * @return array|null
     */
    public function getResponse(): ?object
    {
        return $this->response;
    }
}
