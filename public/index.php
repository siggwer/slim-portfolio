<?php

try {
    $app = new Application();
    $app->init();
    $app->handleRequest();

} catch (Exception $exception) {
    //var_dump($exception->getMessage());
}