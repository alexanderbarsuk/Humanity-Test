<?php
declare(strict_types = 1);

namespace App\Form;

use App\Request;

abstract class AbstractFormField
{
    use FieldValidateTrait;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var
     */
    protected $value;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $error;

    /**
     * @var int
     */
    protected $filter;

    /**
     * @var mixed
     */
    protected $validator;

    /**
     * FormField constructor.
     * @param string $name
     * @param string $title
     * @param int $filter
     * @param int $validator
     * @param string $errorMessage
     */
    public function __construct(string $name, string $title, int $filter, $validator = FILTER_DEFAULT , ?string $errorMessage = null)
    {
        $this->request = Request::getInstance();

        $this->name = $name;
        $this->title = $title;
        $this->filter = $filter;
        $this->validator = $validator;
        $this->error = $errorMessage;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        $val = $this->request->getFilteredInput(INPUT_POST, $this->name, $this->filter);
        return $val ?? $this->value;
    }

    /**
     * @param $value
     * @return AbstractFormField
     */
    public function setValue($value) : AbstractFormField
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @return bool
     */
    public function isValid() : bool
    {
        $methodVariable = array($this, $this->validator);
        if (is_callable($methodVariable, true, $callableName)) {
            $val = $this->request->getFilteredInput(INPUT_POST, $this->name, FILTER_CALLBACK, ['options' => $callableName]);
        } else {
            $val = $this->request->getFilteredInput(INPUT_POST, $this->name, $this->validator);
        }

        return ($val === false) ? false : true; //$val might be null, when null then true!
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getError(): string
    {
        return !$this->isValid() ? $this->error : '';
    }

    /**
     * @param string $error
     * @return AbstractFormField
     */
    public function setError(string $error) : AbstractFormField
    {
        $this->error = $error;
        return $this;
    }
}
