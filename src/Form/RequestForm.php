<?php
declare(strict_types = 1);

namespace Form;

use App\Exception\UserException;
use App\Form\AbstractForm;
use App\Form\AbstractFormField;
use App\Form\Field\DateField;
use App\Form\Field\SelectField;
use App\Response\AbstractOutput;
use App\Response\ResponseOutput\HtmlResponse;
use Entity\RequestEntity;

class RequestForm extends AbstractForm
{
    /**
     * @var AbstractFormField
     */
    private $startDate;

    /**
     * @var AbstractFormField
     */
    private $endDate;

    /**
     * @var AbstractFormField
     */
    private $type;

    /**
     * @var AbstractOutput
     */
    private $template;

    /**
     * RequestForm constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->startDate = new DateField('start_date', 'Start Date', FILTER_SANITIZE_STRING, 'validateDate', 'Date format is wrong or field is empty');
        $this->endDate = new DateField('end_date', 'End Date',  FILTER_SANITIZE_STRING, 'validateDate', 'Date format is wrong or field is empty');

        $this->type = new SelectField('type', 'Type', FILTER_SANITIZE_STRING, 'validateRequestType', 'Type is wrong');
        $this->type->setElements(RequestEntity::TYPE);

        $this->template = new HtmlResponse('form/request', HtmlResponse::EMPTY_LAYOUT);
        $this->template->loadData([
            "startDate" => $this->startDate,
            "endDate" => $this->endDate,
            "type" => $this->type
        ]);

    }

    /**
     * @return DateField
     */
    public function getStartDate(): DateField
    {
        return $this->startDate;
    }

    /**
     * @return DateField
     */
    public function getEndDate(): DateField
    {
        return $this->endDate;
    }

    /**
     * @return SelectField
     */
    public function getType(): SelectField
    {
        return $this->type;
    }

    /**
     * @return AbstractFormField[]
     */
    public function getFields() : array
    {
        return [
            $this->startDate,
            $this->endDate,
            $this->type
        ];
    }

    /**
     * @throws UserException
     * @return bool
     */
    public function validate() : bool
    {
        foreach ($this->getFields() as $field) {
            if (!$field->isValid()) {
                throw new UserException("Please fill in all fields without errors");
            }
        }

        if ($this->startDate->getDateTimeValue() > $this->endDate->getDateTimeValue()) {
            throw new UserException("End date should be greater or equal than start date");
        }
        return true;
    }

    /**
     * @return bool
     */
    public function isSubmitted() : bool
    {
        return $this->request->getFilteredInput(INPUT_POST, 'request', FILTER_SANITIZE_STRING) !== null;
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->template->printPage();
    }
}
