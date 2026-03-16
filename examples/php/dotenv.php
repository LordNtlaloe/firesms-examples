<?php

// dotenv.php — minimal .env loader, no Composer required

function loadEnv(string $path = __DIR__ . '/.env'): void
{
    if (!file_exists($path)) {
        return;
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $line) {
        // Skip comments
        if (str_starts_with(trim($line), '#')) {
            continue;
        }

        [$key, $value] = array_map('trim', explode('=', $line, 2));

        if (!empty($key)) {
            putenv("{$key}={$value}");
            $_ENV[$key] = $value;
        }
    }
}
