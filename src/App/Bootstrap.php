<?php
declare(strict_types = 1);

namespace App;

use App\Exception\UserException;
use Entity\EntityManager\UserManager;
use Entity\UserEntity;
use App\AccessControl\Acl;
use Tools\Session\Session;

class Bootstrap
{
    /**
     * @var Bootstrap
     */
    protected static $instance;

    /**
     * @var AbstractController
     */
    private $controller;

    /**
     * @return Bootstrap
     */
    public static function getInstance() : Bootstrap
    {
        if (null === static::$instance) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    protected function __construct()
    {
    }

    private function __clone()
    {
    }

    /**
     * @param AbstractController $controller
     */
    public function setController(AbstractController $controller)
    {
        $this->controller = $controller;
    }

    public function before() : void
    {
        $session = Session::getInstance();
        $userManager = new UserManager();

        $userId = $session['user_id'];

        $user = new UserEntity();

        if ($userId !== null) {
            $user = $userManager->getById((int)$userId);
        }

        $this->initAcl($user);

        $this->controller->user = $user;
        $this->controller->usersList = $userManager->getList();

        $this->controller->response->title = 'no title';
        $this->controller->response->user = $user;
        $this->controller->response->usersList = $this->controller->usersList;
        $this->controller->response->appName = Main::$appConfig->app_name;
    }

    public function after() : void
    {

    }

    /**
     * @param UserEntity $user
     * @throws UserException
     */
    private function initAcl(UserEntity $user) : void
    {
        $acl = new Acl();

        $acl->addRole(UserEntity::USER_ROLES['GUEST']);
        $acl->addRole(UserEntity::USER_ROLES['USER'], UserEntity::USER_ROLES['GUEST']);
        $acl->addRole(UserEntity::USER_ROLES['MANAGER'], UserEntity::USER_ROLES['USER']);

        $acl->allow(UserEntity::USER_ROLES['GUEST'], 'authorization', ['login']);
        $acl->allow(UserEntity::USER_ROLES['GUEST'], 'error');
        $acl->allow(UserEntity::USER_ROLES['GUEST'], 'index');

        $acl->allow(UserEntity::USER_ROLES['USER'], 'authorization', ['logout']);
        $acl->allow(UserEntity::USER_ROLES['USER'], 'request', ['show_make_request']);
        $acl->allow(UserEntity::USER_ROLES['USER'], 'request', ['show_list']);
        $acl->allow(UserEntity::USER_ROLES['USER'], 'request', ['view']);
        $acl->allow(UserEntity::USER_ROLES['USER'], 'request', ['edit']);
        $acl->allow(UserEntity::USER_ROLES['USER'], 'request', ['delete']);

        $acl->allow(UserEntity::USER_ROLES['MANAGER'], 'request');

        if (!$acl->isAllowed($user->getRole(), $this->controller->router->getController(), $this->controller->router->getAction())) {
            throw new UserException("Access denied: " . $this->controller->router->getController() . " -> " . $this->controller->router->getAction());
        }
    }
}