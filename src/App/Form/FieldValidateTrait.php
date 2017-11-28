<?php
declare(strict_types = 1);

namespace App\Form;

use Entity\RequestEntity;

trait FieldValidateTrait
{
    /**
     * @param $value
     * @return bool
     */
    public static function  validateDate($value) : bool
    {
        if (empty($value)) {
            return false;
        }
        try {
            new \DateTime($value);
            return true;
        } catch (\Exception $e){
            return false;
        }
    }

    /**
     * @param $value
     * @return bool
     */
    public static function  validateRequestType($value) : bool
    {
        return isset((RequestEntity::TYPE)[$value]);
    }
}
