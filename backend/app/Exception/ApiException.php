<?php

namespace SkPro\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class ApiException extends HttpException
{
    protected array $data;

    protected array $payload;

    public function __construct(
        string|array $message = 'Unexpected error',
        int $statusCode = 422,
        ?Throwable $previous = null,
        array $headers = []
    ) {
        $this->payload = is_array($message)
            ? $message
            : ['message' => $message];

        parent::__construct($statusCode, $this->payload['message'], $previous, $headers);
    }

    public function toArray(): array
    {
        return $this->payload;
    }


    public function render()
    {
        return response()->json(
            $this->toArray(),
            $this->getStatusCode(),
            $this->getHeaders()
        );
    }
}
