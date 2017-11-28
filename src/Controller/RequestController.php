<?php
declare(strict_types = 1);

namespace Controller;

use App\AbstractController;
use App\Exception\UserException;
use App\Response\Response;
use App\Response\ResponseOutput\HtmlResponse;
use Entity\EntityManager\RequestManager;
use Entity\RequestEntity;
use Entity\UserEntity;
use Form\RequestForm;

class RequestController extends AbstractController
{

    public function before() : void
    {
        parent::before();

    }

    public function actionShowMakeRequest() : void
    {
        $form = new RequestForm();
        if ($form->isSubmitted()) {
            try {
                $form->validate();

                $requestEntity = new RequestEntity();
                $requestEntity->setStartDate($form->getStartDate()->getDateTimeValue());
                $requestEntity->setEndDate($form->getEndDate()->getDateTimeValue());
                $requestEntity->setStatus(RequestEntity::STATUS['NEW']);
                $requestEntity->setType($form->getType()->getValue());
                $requestEntity->setUserEntity($this->user);

                $requestManager = new RequestManager();
                $requestManager->add($requestEntity);

                $this->response->addMessage("You request has been added to manager's queue. You requested " . $requestEntity->getDuration() . " days" , Response::ALERT_CLASS['SUCCESS']);
                $this->redirect("/requests");
            } catch (UserException $e) {
                $this->response->addMessage($e->getMessage(), Response::ALERT_CLASS['DANGER']);
            }

        }

        $this->response->form = $form;

        $this->response->title = "Make new request";
        $this->response->render(new HtmlResponse('view/request/leave'));
    }

    public function actionShowList() : void
    {
        $requestManager = new RequestManager();

        $this->response->requests = $this->user->getRole() == UserEntity::USER_ROLES['MANAGER']
            ? $requestManager->getList()
            : $requestManager->getListByUserId($this->user->getId());

        $this->response->title = "List of requests";
        $this->response->render(new HtmlResponse('view/request/list'));
    }

    /**
     * @param $requestId
     * @throws UserException
     */
    public function actionView(string $requestId) : void
    {
        $requestManager = new RequestManager();
        $request = $requestManager->getById((int)$requestId);

        if ($this->user->getRole() == UserEntity::USER_ROLES['USER'] and $request->getUserEntity()->getId() != $this->user->getId()) {
            throw new UserException("Access denied. You can not view this request");
        }

        $this->response->request = $request;

        $this->response->title = "View request: " . $request->getUserEntity()->getName();
        $this->response->render(new HtmlResponse('view/request/view'));
    }

    public function actionEdit(string $requestId) : void
    {
        $requestManager = new RequestManager();
        $requestEntity = $requestManager->getById((int)$requestId);

        if ($this->user->getRole() == UserEntity::USER_ROLES['USER'] and $requestEntity->getUserEntity()->getId() != $this->user->getId()) {
            throw new UserException("Access denied. You can not view this request");
        }

        if ($this->user->getRole() == UserEntity::USER_ROLES['USER'] and $requestEntity->getStatus() != RequestEntity::STATUS['NEW']) {
            throw new UserException("Access denied. You can not edit not NEW request");
        }

        $form = new RequestForm();
        $form->getStartDate()->setDateTimeValue($requestEntity->getStartDate());
        $form->getEndDate()->setDateTimeValue($requestEntity->getEndDate());
        $form->getType()->setValue($requestEntity->getType());

        if ($form->isSubmitted()) {
            try {
                $form->validate();

                $requestEntity->setStartDate($form->getStartDate()->getDateTimeValue());
                $requestEntity->setEndDate($form->getEndDate()->getDateTimeValue());
                $requestEntity->setStatus(RequestEntity::STATUS['NEW']);
                $requestEntity->setType($form->getType()->getValue());

                $requestManager = new RequestManager();
                $requestManager->update($requestEntity);

                $this->response->addMessage("Request has been updated. You requested " . $requestEntity->getDuration() . " days" , Response::ALERT_CLASS['SUCCESS']);
                $this->redirect("/request/view/" . $requestEntity->getId());
            } catch (UserException $e) {
                $this->response->addMessage($e->getMessage(), Response::ALERT_CLASS['DANGER']);
            }
        }

        $this->response->form = $form;
        $this->response->title = "Edit request: " . $requestEntity->getUserEntity()->getName();
        $this->response->render(new HtmlResponse('view/request/edit'));
    }

    public function actionDelete(string $requestId) : void
    {
        $requestManager = new RequestManager();
        $requestEntity = $requestManager->getById((int)$requestId);

        if ($this->user->getRole() == UserEntity::USER_ROLES['USER'] and $requestEntity->getUserEntity()->getId() != $this->user->getId()) {
            throw new UserException("Access denied. You can not delete this request");
        }

        if ($this->user->getRole() == UserEntity::USER_ROLES['USER'] and $requestEntity->getStatus() != RequestEntity::STATUS['NEW']) {
            throw new UserException("Access denied. You can not delete not NEW request");
        }

        try {
            $requestManager->delete($requestEntity, $this->user);
            $this->response->addMessage("Request has been successfully deleted", Response::ALERT_CLASS['SUCCESS']);
        } catch (UserException $e) {
            $this->response->addMessage($e->getMessage(), Response::ALERT_CLASS['DANGER']);
        }

        $this->redirectBack();
    }

    public function actionChangeStatus(string $requestId, string $status) : void
    {
        $requestManager = new RequestManager();
        $requestEntity = $requestManager->getById((int)$requestId);

        if ($requestEntity->getStatus() != RequestEntity::STATUS['NEW']) {
            throw new UserException("Access denied. You can not change status for not NEW request");
        }

        if (!isset((RequestEntity::STATUS)[$status]) or $status == RequestEntity::STATUS['NEW']) {
            throw new UserException("Wrong status");
        }

        $requestEntity->setStatus($status);

        try {
            if ($status == RequestEntity::STATUS['APPROVED'] and $requestEntity->getDuration() > $this->usersList[$requestEntity->getUserEntity()->getId()]->getDaysLeft($requestEntity->getType())) {
                throw new UserException("User has not enough days");
            }

            $requestManager->changeStatusRequest($requestEntity, $this->user);
            $this->response->addMessage("Request has been successfully {$status}", Response::ALERT_CLASS['SUCCESS']);
        } catch (UserException $e) {
            $this->response->addMessage($e->getMessage(), Response::ALERT_CLASS['DANGER']);
        }

        $this->redirectBack();
    }
}
