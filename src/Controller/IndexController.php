<?php
declare(strict_types = 1);

namespace Controller;

use App\AbstractController;
use App\Response\ResponseOutput\HtmlResponse;

class IndexController extends AbstractController
{
    public function actionIndex() : void
    {
        $this->response->title = "Main page";
        $this->response->render(new HtmlResponse('view/index/index'));
    }
}