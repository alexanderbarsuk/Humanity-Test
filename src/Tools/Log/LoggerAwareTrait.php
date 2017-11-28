<?php
declare(strict_types = 1);

namespace Tools\Log;

trait LoggerAwareTrait
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger) : void
    {
        $this->logger = $logger;
    }
}
