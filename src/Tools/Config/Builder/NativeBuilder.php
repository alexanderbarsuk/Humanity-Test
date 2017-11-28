<?php
declare(strict_types = 1);

namespace Tools\Config\Builder;

class NativeBuilder extends AbstractBuilder
{

    /**
     * @return array
     */
    public function parse(): array
    {
        $result = include($this->fileName);

        if (!is_array($result)) {
            throw new \UnexpectedValueException("Invalid config structure");
        }

        return $result;
    }
}
