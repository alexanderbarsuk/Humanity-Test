<?php
declare(strict_types = 1);

namespace App\Form\Field;

use App\Form\AbstractFormField;

class DateField extends AbstractFormField
{
    /**
     * @var string
     */
    protected $type = 'date';

    /**
     * @return \DateTime
     */
    public function getDateTimeValue() : \DateTime
    {
        $value = parent::getValue();
        return new \DateTime($value);
    }

    /**
     * @param \DateTime $value
     * @return DateField
     */
    public function setDateTimeValue(\DateTime $value) : DateField
    {
        $this->value = $value->format('Y-m-d');
        return $this;
    }
}
