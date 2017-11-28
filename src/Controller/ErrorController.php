<?php
declare(strict_types = 1);

namespace Controller;

use App\AbstractController;
use App\Response\ResponseOutput\HtmlResponse;

class ErrorController extends AbstractController
{
    public function actionServer() : void
    {
        $this->response->title = "Unhandled server error";
        $this->response->error = "Server error";
        $this->response->render(new HtmlResponse('view/error/server', 'layout/error'));
    }

    public function actionNotFound() : void
    {
        $this->response->title = "Not Found error";
        $this->response->error = "Page does not exist";
        $this->response->render(new HtmlResponse('view/error/not_found', 'layout/error'));
    }
}
