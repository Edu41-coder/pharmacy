<?php

namespace App\Logger;

use Monolog\Formatter\LineFormatter;

class UTF8LineFormatter extends LineFormatter
{
    public function __construct()
    {
        parent::__construct(
            "[%datetime%] %channel%.%level_name%: %message% %context% %extra%\n",
            "Y-m-d H:i:s",
            true,
            true,
            true
        );
    }

    protected function convertToString($data): string
    {
        if (is_array($data)) {
            return json_encode($data, JSON_UNESCAPED_UNICODE);
        }
        return parent::convertToString($data);
    }
} 