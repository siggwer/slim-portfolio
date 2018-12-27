<?php

use Slim\Http\Request;
use Slim\Http\Response;

// All routes
//return [
    //'home' => [
        //'methods' => ['GET'],
        //'path' => '/',
        //'controller' => App\Controller\HomeController::class,
        //'middlewares' => []
    //]
//];

//$app->get('/', function (Request $request, Response $response) {
    //$response->getBody()->write("It works! This is the default welcome page.");
    //$response->view->render($response, '../Views/home.twig');

    //return $response;
//})->setName('root');

//$app->get('/hello/{name}', function (Request $request, Response $response) {
    //$name = $request->getAttribute('name');
    //$response->getBody()->write("Hello, $name");

    //return $response;
//});

$app->get('/', function (Request $request, Response $response, array $args) {
    // Sample log message
    //$this->logger->info("Slim-Skeleton '/' route");

    // Render index view
    return $this->view->render($response, 'layout.html.twig', $args);
});