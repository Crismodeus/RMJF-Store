<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

// Sólo defino si NO existe ya la constante
if (! defined('DB_HOST'))  define('DB_HOST',  'localhost');
if (! defined('DB_NAME'))  define('DB_NAME',  'RMJF1');
if (! defined('DB_USER'))  define('DB_USER',  'root');
if (! defined('DB_PASS'))  define('DB_PASS',  '');
if (! defined('BASE_URL')) define('BASE_URL', 'http://localhost/tiendav2');

if (! function_exists('url')) {
    function url(string $path = ''): string {
        return rtrim(BASE_URL, '/') . '/' . ltrim($path, '/');
    }
}


