<?php
declare(strict_types = 1);

namespace Tools\Log;

interface LoggerAwareInterface
{
    /**
     * Sets a logger to the object
     *
     * @param LoggerInterface $logger
     * @return void
     */
    public function setLogger(LoggerInterface $logger) : void;
}