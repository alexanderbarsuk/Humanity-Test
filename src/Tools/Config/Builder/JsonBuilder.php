<?php
declare(strict_types = 1);

namespace Tools\Config\Builder;


class JsonBuilder extends AbstractBuilder
{

    /**
     * @throws \UnexpectedValueException
     * @return array
     */
    public function parse(): array
    {
        $result = @json_decode(@file_get_contents($this->fileName), true);

        if ($result === false) {
            throw new \UnexpectedValueException(json_last_error_msg());
        } elseif (!is_array($result)) {
            return [];
        }

        return $result;
    }
}
