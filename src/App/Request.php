<?php
declare(strict_types = 1);

namespace App;

class Request
{

    /**
     * @var Request The reference to instance of this class
     */
    protected static $instance;

    /**
     * Returns the  instance of this class.
     *
     * @return Request The instance.
     */
    public static function getInstance() : Request
    {
        if (null === static::$instance) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    /**
     * Gets a specific external variable by name and optionally filters it
     *
     * @param int $type
     * @param string $variableName
     * @param int $filter
     * @param mixed $options
     * @return mixed
     */
    public function getFilteredInput(int $type , string $variableName, int $filter = FILTER_DEFAULT, $options = null) //:mixed
    {
        $filterResult = filter_input($type, $variableName, $filter, $options);

        return $filterResult;
    }

    /**
     * Gets external variables and optionally filters them
     *
     * @param int $type
     * @param null $definition
     * @param bool $addEmpty
     * @return mixed
     */
    public function getFilteredInputArray(int $type , $definition = null, bool $addEmpty = true ) : array
    {
        $filterResult = filter_input_array($type, $definition, $addEmpty);

        return $filterResult;
    }

    /**
     * @return string
     */
    public function getHost() : string
    {
        return $this->getFilteredInput(INPUT_SERVER, 'HTTP_HOST', FILTER_SANITIZE_STRING );
    }

    /**
     * @return string
     */
    public function getScheme() : string
    {
        return $this->getFilteredInput(INPUT_SERVER, 'REQUEST_SCHEME', FILTER_SANITIZE_STRING );
    }

    /**
     * Request constructor.
     */
    protected function __construct()
    {
    }

    /**
     * @return void
     */
    private function __clone()
    {
    }

}
