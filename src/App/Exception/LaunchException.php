<?php
declare(strict_types = 1);

namespace App\Exception;

use App\Main;
use Throwable;
use Tools\Log\LoggerAwareInterface;
use Tools\Log\LoggerAwareTrait;

class LaunchException extends \Exception implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    public function __construct($message = "", int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->setLogger(Main::$appLogger);

        $this->logger->critical($message);
    }
}
