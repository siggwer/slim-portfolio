<?php

session_start();

require_once __DIR__ .'/../vendor/autoload.php';

try {
    $app = new \Slim\App([
        'settings' => [
            'displayErrorDetails' => true
        ]
    ]);

    $app->get('/', 'homeController');

    $app->run();
    //$app->handleRequest();

} catch (Exception $exception) {
    //var_dump($exception->getMessage());
}