<?php
declare(strict_types = 1);

namespace Controller;

use App\AbstractController;
use App\Response\ResponseOutput\HtmlResponse;
use Tools\Session\Session;

class AuthorizationController extends AbstractController
{
    public function actionLogin($userId) : void
    {
        $session = Session::getInstance();
        $session['user_id'] = $userId;

        $this->redirectBack();
    }

    public function actionLogout() : void
    {
        $session = Session::getInstance();
        unset($session['user_id']);

        $this->redirect('/');
    }
}
