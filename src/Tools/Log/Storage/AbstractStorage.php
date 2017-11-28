<?php
declare(strict_types = 1);

namespace Tools\Log\Storage;

abstract class AbstractStorage
{
    /**
     * @param string $message
     * @return mixed
     */
    abstract public function store(string $message) : void;
}
