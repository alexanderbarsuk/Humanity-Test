<?php
declare(strict_types = 1);

namespace App\Response\ResponseOutput;

use App\Response\AbstractOutput;

class JsonResponse extends AbstractOutput
{
    /**
     * JsonResponse constructor.
     */
    public function __construct()
    {
        $this->addHeader('Content-type: text/json');
    }

    /**
     * @return string
     */
    public function printPage() : string
    {
        return json_encode($this->vars);
    }
}
