<?php
declare(strict_types = 1);

namespace App\Form;

use App\Request;

abstract class AbstractForm
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * AbstractForm constructor.
     */
    public function __construct()
    {
        $this->request = Request::getInstance();
    }

    /**
     * @return bool
     */
    abstract public function validate() : bool;

    /**
     * @return bool
     */
    abstract public function isSubmitted() : bool;

    /**
     * @return string
     */
    abstract public function getBody() : string;

}
