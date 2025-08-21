<?php

// Development router for PHP built-in server
// Routes all non-existing files to index.php

if (php_sapi_name() === 'cli-server') {
    $requestedPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $filePath = __DIR__ . $requestedPath;

    if (is_file($filePath)) {
        return false;
    }
}

require __DIR__ . '/index.php';

