<?php
declare(strict_types = 1);

namespace Entity;

abstract class AbstractEntityManager
{

    abstract public function getList() : array;

}