<?php
declare(strict_types = 1);

namespace App;

use Tools\Log\LoggerAwareInterface;
use Tools\Log\LoggerAwareTrait;

class Router implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    const ELEMENTS_DELIMITER = '/';
    const ACTION_PREFIX = 'action';
    const ACTION_DELIMITER = '_';
    const CONTROLLER_POSTFIX = 'Controller';
    const CONTROLLER_BASE = '\\Controller\\';

    /**
     * @var array
     */
    private $routes = [];

    /**
     * @var string
     */
    private $controller = '';

    /**
     * @var string
     */
    private $controllerClassName = '';

    private $action = '';

    /**
     * @var string
     */
    private $actionName = '';

    /**
     * @var array
     */
    private $params = [];

    /**
     * Router constructor.
     */
    public function __construct()
    {
        $this->setLogger(Main::$appLogger);
    }

    /**
     * @return string
     */
    public function getController() : string
    {
        return $this->controller;
    }

    /**
     * @return string
     */
    public function getControllerClassName() : string
    {
        return self::CONTROLLER_BASE . $this->controllerClassName;
    }

    /**
     * @return string
     */
    public function getAction() : string
    {
        return $this->action;
    }

    /**
     * @return string
     */
    public function getActionName() : string
    {
        return $this->actionName;
    }

    /**
     * @return array
     */
    public function getParams() : array
    {
        return $this->params;
    }

    /**
     * @param string $pattern
     * @param string $router
     */
    public function connect(string $pattern, string $router) : void
    {
        $this->routes[$pattern] = $router;
    }

    /**
     * @return bool
     */
    public function matchRouteFromConfig() : bool
    {

        $url = $this->getPreparedLink();
        $route = '';

        foreach ($this->routes as $key => $value) {
            $pattern = '`^' . $key . '$`Ui';

            if (preg_match($pattern, $url)) {
                $route = preg_replace($pattern, $value, $url);
            }
        }

        if ($route != '') {
            return $this->processRoute($route);
        }

        return false;
    }

    /**
     * @param string $route
     * @return bool
     */
    public function processRoute(string $route) : bool
    {
        $rElements = explode(self::ELEMENTS_DELIMITER, $route);

        $this->controller = array_shift($rElements);
        $this->controllerClassName = ucfirst($this->controller);
        $this->controllerClassName = !empty($this->controllerClassName) ? $this->controllerClassName . self::CONTROLLER_POSTFIX : null;

        $this->action = array_shift($rElements);
        $actionElements = explode(self::ACTION_DELIMITER, $this->action);
        array_walk($actionElements, function(&$element) { $element = ucfirst(strtolower($element));});
        $this->actionName = implode('', $actionElements);
        $this->actionName = !empty($this->actionName) ? self::ACTION_PREFIX . $this->actionName : null;

        $this->params = $rElements;

        if ($this->controllerClassName !== null and $this->actionName !== null) {
            return true;
        }
        return false;
    }

    /**
     * @return string
     */
    public function getPreparedLink(): string
    {
        $link = Request::getInstance()
            ->getFilteredInput(INPUT_SERVER, 'REDIRECT_URL', FILTER_SANITIZE_STRING);
        return \trim($link, '/');
    }
}
