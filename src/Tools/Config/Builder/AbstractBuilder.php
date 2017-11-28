<?php
declare(strict_types = 1);

namespace Tools\Config\Builder;

abstract class AbstractBuilder
{
    /**
     * @var string
     */
    protected $fileName;

    /**
     * AbstractBuilder constructor.
     * @param string $fileName
     * @throws \Exception
     */
    public function __construct(string $fileName)
    {
        if (!file_exists($fileName)) {
            throw new \Exception("File not found");
        }
        $this->fileName = $fileName;
    }

    /**
     * @throws \UnexpectedValueException
     * @return array
     */
    abstract public function parse() : array;
}