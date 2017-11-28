<?php
declare(strict_types = 1);

namespace App\Response;

abstract class AbstractOutput
{
    /**
     * @var array
     */
    protected $headers = [];

    /**
     * @var array
     */
    protected $vars = [];

    /**
     * @param string $header
     * @return AbstractOutput
     */
    public function addHeader(string $header) : AbstractOutput
    {
        $headers[] = $header;
        return $this;
    }

    /**
     * @param array $data
     */
    public function loadData(array $data) : void
    {
        $this->vars = $data;
    }


    public function printHeaders() : void
    {
        if (!headers_sent()) {
            foreach ($this->headers as $header) {
                header($header);
            }
        }
    }

    /**
     * @return string
     */
    abstract public function printPage() : string;
}
