<?php

declare(strict_types=1);

use Framework\Http;

function dd(mixed $value): void
{
    echo "<pre>";
    var_dump($value);
    echo "</pre>";

    die();
}

function escape(mixed $value): string
{
    return htmlspecialchars((string) $value);
}

function redirectTo(string $url)
{
    header("Location: $url");
    http_response_code(Http::REDIRECT_STATUS_CODE);
    exit;
}
