<?php
declare(strict_types = 1);

namespace App\Form\Field;

use App\Form\AbstractFormField;

class SelectField extends AbstractFormField
{
    /**
     * @var string
     */
    protected $type = 'select';

    /**
     * @var array
     */
    protected $elements;

    /**
     * @return array
     */
    public function getElements(): array
    {
        return $this->elements;
    }

    /**
     * @param array $elements
     * @return SelectField
     */
    public function setElements(array $elements) : SelectField
    {
        $this->elements = $elements;
        return $this;
    }


}
