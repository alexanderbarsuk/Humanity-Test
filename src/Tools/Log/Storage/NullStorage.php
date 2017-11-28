<?php
declare(strict_types = 1);

namespace Tools\Log\Storage;

class NullStorage extends AbstractStorage
{
    /**
     * @param string $message
     */
    public function store(string $message) : void
    {
        return;
    }
}
