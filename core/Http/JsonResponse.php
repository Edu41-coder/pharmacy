<?php

namespace Core\Http;

class JsonResponse extends Response
{
    protected const JSON_OPTIONS = JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES;

    public function __construct($data = null, int $status = 200, array $headers = [])
    {
        $defaultHeaders = [
            'Content-Type' => 'application/json; charset=UTF-8',
            'X-Content-Type-Options' => 'nosniff'
        ];
        $headers = array_merge($defaultHeaders, $headers);

        if (!is_string($data)) {
            $jsonContent = json_encode($data, self::JSON_OPTIONS);
            if ($jsonContent === false) {
                throw new \RuntimeException('Failed to encode response to JSON: ' . json_last_error_msg());
            }
        } else {
            json_decode($data);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \RuntimeException('Invalid JSON string provided to JsonResponse');
            }
            $jsonContent = $data;
        }

        parent::__construct($jsonContent, $status, $headers);
    }

    public static function success($data = null, int $status = 200): self
    {
        $response = [
            'success' => true,
            'data' => $data
        ];

        if (is_string($data)) {
            $response['message'] = $data;
            $response['data'] = null;
        }

        return new self($response, $status);
    }

    public static function error(
        string $message,
        int $status = 400,
        ?array $errors = null
    ): self {
        $response = [
            'success' => false,
            'message' => $message,
            'code' => $status
        ];

        if ($errors !== null) {
            $response['errors'] = $errors;
        }

        return new self($response, $status);
    }
} 