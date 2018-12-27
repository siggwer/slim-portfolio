<?php

if (PHP_SAPI == 'cli-server') {
    // To help the built-in PHP dev server, check if the request was actually for
    // something which should probably be served as a static file
    $url  = parse_url($_SERVER['REQUEST_URI']);
    $file = __DIR__ . $url['path'];
    if (is_file($file)) {
        return false;
    }
}

session_start();

require __DIR__ . '/../vendor/autoload.php';

// Instantiate the app
$configuration = require __DIR__ . '/../config/configuration.php';
$app = new \Slim\App($configuration);

// Set up dependencies
require __DIR__ . '/../config/di.php';

// Set up render
require __DIR__ . '/../config/render.php';

// Register middleware
require __DIR__ . '/../config/middleware.php';

// Register routes
require __DIR__ . '/../config/routes.php';


$app->run();