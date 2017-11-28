<?php
declare(strict_types = 1);

namespace App;

use App\Exception\LaunchException;
use App\Exception\NotFoundException;
use App\Exception\UserException;
use Tools\Config\Builder\NativeBuilder;
use Tools\Config\Config;
use Tools\Log\Logger;

class Main
{
    const ROUTES_CONFIG_PATH = 'config/routes.php';
    const DEFAULT_NOT_FOUND_ROUTE = 'error/not_found';
    const DEFAULT_SERVER_ERROR_ROUTE = 'error/server';

    /**
     * @var Main
     */
    protected static $instance;

    /**
     * @var Config
     */
    public static $appConfig;

    /**
     * @var Logger
     */
    public static $appLogger;

    /**
     * @var Router
     */
    private $router;

    /**
     * @var Config
     */
    private $routerConfig;

    /**
     * Main constructor.
     */
    protected function __construct()
    {
        $routeFound = $this->initRouter();

        $appLaunched = false;
        $e = null;

        try {
            if (!$routeFound) {
                throw new NotFoundException("Route does not found: " . $this->router->getPreparedLink());
            }
            $appLaunched = $this->launch();
        } catch (NotFoundException $e) {
            $this->router->processRoute(self::$appConfig->error_processor['not_found'] ?? self::DEFAULT_NOT_FOUND_ROUTE);
        } catch (UserException $e) {
            $this->router->processRoute(self::$appConfig->error_processor['server_error'] ?? self::DEFAULT_SERVER_ERROR_ROUTE);
        } catch (LaunchException $e) {
            $this->router->processRoute(self::$appConfig->error_processor['server_error'] ?? self::DEFAULT_SERVER_ERROR_ROUTE);
        } catch (\Exception $e) {
            $this->router->processRoute(self::$appConfig->error_processor['server_error'] ?? self::DEFAULT_SERVER_ERROR_ROUTE);
        }

        if ($appLaunched !== true) {
            $appLaunched = $this->launch($e);
        }

        if ($appLaunched !== true) {
            echo 'sorry :(';
        }
    }


    private function __clone()
    {

    }

    private function __wakeup()
    {

    }

    /**
     * Entry point for application
     *
     * @param Config $appConfig
     * @param Logger $appLogger
     * @return bool
     */
    public static function start(Config $appConfig, Logger $appLogger) : bool
    {
        if (is_null(static::$instance)) {
            static::$appConfig = $appConfig;
            static::$appLogger = $appLogger;
            static::$instance = new static();

            return true;
        }
        return false;
    }

    /**
     * @return Main
     */
    public static function getApp() : ?Main
    {
        return static::$instance;
    }

    /**
     * @return Router
     */
    public function getRouter() : Router
    {
        return $this->router;
    }

    /**
     * @return bool
     */
    private function initRouter() : bool
    {
        $this->routerConfig = new Config(new NativeBuilder(self::ROUTES_CONFIG_PATH));
        $this->router = new Router();

        foreach ($this->routerConfig->asArray() as $pattern => $rule) {
            $this->router->connect($pattern, $rule);
        }

        return $this->router->matchRouteFromConfig();
    }

    /**
     * @param \Exception|null $exception
     * @return bool
     * @throws LaunchException
     * @throws NotFoundException
     */
    private function launch(\Exception $exception = null) : ?bool
    {

        $controller = $this->router->getControllerClassName();
        $action = $this->router->getActionName();
        $params = $this->router->getParams();

        if (!class_exists($controller, true)) {
            throw new NotFoundException("Controller class does not found " . $controller);
        }

        if (empty($action)) {
            throw new NotFoundException("Action could not be empty");
        }

        $reflectionClass = new \ReflectionClass($controller);

        if ($reflectionClass->hasMethod($action)) {
            $controllerClass = $reflectionClass->newInstance();
            $actionMethod = $reflectionClass->getMethod($action);

            if ($exception !== null) {
                $controllerClass->response->exception = $exception;
            }

            if ($actionMethod->getNumberOfRequiredParameters() <= count($params)) {
                $controllerClass->router = $this->router;
                $controllerClass->before();
                $actionMethod->invokeArgs($controllerClass, $params);
                $controllerClass->after();
            } else {
                throw new LaunchException("Not enough required parameters");
            }
        } else {
            throw new NotFoundException("Missing method in controller class");
        }

        return true;
    }

}
