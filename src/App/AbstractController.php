<?php
declare(strict_types = 1);

namespace App;

use App\Response\Response;
use Entity\UserEntity;
use Tools\Log\LoggerAwareInterface;
use Tools\Log\LoggerAwareTrait;

abstract class AbstractController implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * @var Response
     */
    public $response;

    /**
     * @var Request
     */
    public $request;

    /**
     * @var Router
     */
    public $router;

    /**
     * @var UserEntity
     */
    public $user;

    /**
     * @var UserEntity[]
     */
    public $usersList;

    /**
     * AbstractController constructor.
     */
    final public function __construct()
    {
        $this->setLogger(Main::$appLogger);

        $this->request = Request::getInstance();
        $this->response = Response::getInstance();

    }

    public function before() : void
    {
        Bootstrap::getInstance()->setController($this);
        Bootstrap::getInstance()->before();
    }

    public function after() : void
    {
        Bootstrap::getInstance()->after();
    }

    /**
     * @param null|string $url
     */
    final public function redirect(?string $url = null) : void
    {
        header("Location: {$url}");
        die;
    }

    final public function redirectBack() : void
    {
        header("Location: {$this->request->getFilteredInput(INPUT_SERVER, 'HTTP_REFERER')}");
        die;
    }

    final public function __destruct()
    {

    }
}
