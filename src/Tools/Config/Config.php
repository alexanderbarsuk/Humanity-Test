<?php
declare(strict_types = 1);

namespace Tools\Config;

use Tools\Config\Builder\AbstractBuilder;

class Config
{
    /**
     * @var array
     */
    private $data = [];

    /**
     * Config constructor.
     * @param AbstractBuilder $builder
     */
    public function __construct(AbstractBuilder $builder)
    {
        $this->data = $builder->parse();
    }

    /**
     * @param string $name
     * @return mixed|null
     */
    public function __get(string $name)
    {
        if (isset($this->data[$name])) {
            return $this->data[$name];
        }
        return null;
    }

    /**
     * Returns full config as array
     *
     * @return array
     */
    public function asArray() : array
    {
        return $this->data;
    }
}
