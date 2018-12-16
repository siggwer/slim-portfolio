<?php

// All routes
return [
    'home' => [
        'methods' => ['GET'],
        'path' => '/',
        'controller' => App\Controller\HomeController::class,
        'middlewares' => []
    ]
];