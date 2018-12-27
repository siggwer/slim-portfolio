<?php

namespace Framework;

use Psr\Http\Message\ResponseInterface;
class Controller
{
    /**
     * @var $container
     */
    private $container;

    /**
     * Controller constructor.
     * @param $container
     */
    public function __construct($container)
    {
        $this->container = $container;
    }

    /**
     * @param ResponseInterface $response
     * @param $file
     * @param array $params
     */
    public function render(ResponseInterface $response, $file, $params = []) {
        $this->container->view->render($response, $file, $params);
    }

    /**
     * @param $response
     * @param $name
     * @param int $status
     * @return mixed
     */
    public function redirect($response, $name, $status = 302) {
        return $response->withStatus($status)->withHeader('Location', $this->router->pathFor($name));
    }

    /**
     * @param $message
     * @param string $type
     * @return mixed
     */
    public function flash($message, $type = 'success') {
        if (!isset($_SESSION['flash'])) {
            $_SESSION['flash'] = [];
        }
        return $_SESSION['flash'][$type] = $message;
    }

    /**
     * @param $name
     * @return mixed
     */
    public function __get($name) {
        return $this->container->get($name);
    }
}