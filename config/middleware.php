<?php

//Middlewares
$container = $app->getContainer();
$container['HomeController'] = function($c) {
    $view = $c->get("view"); // retrieve the 'view' from the container
    return new HomeController($view);
};