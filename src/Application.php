<?php

use Interop\Container\ContainerInterface;
use Jgut\Slim\PHPDI\Configuration;
use Jgut\Slim\PHPDI\App;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class Application
{
    /**
     * @var Container $container
     */
    private $container;

    /**
     * @var ServerRequestInterface $request
     */
    private $request;

    /**
     * @var ResponseInterface $response
     */
    private $response;

    /**
     * @var array
     */
    private $middlewares;

    /**
     * @var FastRouteRouter $router
     */
    private $router;

    public function __construct()
    {
        $this->middlewares = [];
    }


    public function init()
    {
        //$containerBuilder = new ContainerBuilder();
        //$containerBuilder->useAutowiring(true);
        //$containerBuilder->addDefinitions(__DIR__.'/../configs/dic/database.php');
        //$containerBuilder->addDefinitions(__DIR__.'/../configs/dic/repositories.php');
        //$containerBuilder->addDefinitions(__DIR__.'/../configs/dic/render.php');
        //$containerBuilder->addDefinitions(__DIR__. '/../configs/dic/SwiftMailer.php');
        //$this->container = $containerBuilder->build();

        $settings = require __DIR__ . '/settings.php';
        $configuration = new Configuration($settings);
        $configuration->setDefinitions('/path/to/definitions/file.php');

        $app = new App($configuration);
        $container = $app->getContainer();

        // Register services the PHP-DI way
        $container->set('service_one', function (ContainerInterface $container) {
            return new ServiceOne($container->get('service_two'));
        });

        // \Jgut\Slim\PHPDI\Container accepts registering services à la Pimple
        $container['service_two'] =  function (ContainerInterface $container) {
            return new ServiceTwo();
        };


        $this->initRouter();
    }

    /**
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function run()
    {
        $this->request = ServerRequest::fromGlobals();
        $this->response = new Response();

        $route = $this->router->match($this->request);
        if ($route->isSuccess()) {
            foreach ($route->getMatchedParams() as $name => $value) {
                $this->request = $this->request->withAttribute($name, $value);
            }

            $middlewares = $this->middlewares[$route->getMatchedRouteName()];
            if ($middlewares === null) {
                $middlewares = [];
            }

            $middlewaresGlobals = (require __DIR__.'/../app/Middlewares/GlobalsMiddlewares/Middlewares.php');
            $middlewares = array_merge($middlewaresGlobals, $middlewares);

            $dispatcher = new Dispatcher($this->container, $middlewares);
            $dispatcher->pipe($route->getMatchedMiddleware());
            $result = $dispatcher->process($this->request, $this->response);

            $location = $result->getHeader('Location');
            if (!empty($location)) {
                header("HTTP/{$result->getProtocolVersion()} 301 Moved Permantly", false, 301);
                header('Location: '.$location[0]);
                exit();
            }

            send_response($result);
        } else {
            $rendering = $this->container->get(RenderInterface::class)->render('Errors/404');
            send_response(new Response(404, [], $rendering));
        }
    }

    private function initRouter()
    {
        $this->router = new FastRouteRouter();

        $routes = (require __DIR__.'/../app/Routes.php');
        foreach ($routes as $name => $route) {
            $routeAdd = new Route($route['path'], $route['action'], $route['methods'], $name);
            $this->router->addRoute($routeAdd);

            $this->middlewares[$name] = $route['middlewares'];
        }
    }
}