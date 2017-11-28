<?php
declare(strict_types = 1);

namespace Tools\Log;

class LogLevel
{
    const EMERGENCY = LoggerInterface::LEVEL['EMERGENCY'];
    const ALERT     = LoggerInterface::LEVEL['ALERT'];
    const CRITICAL  = LoggerInterface::LEVEL['CRITICAL'];
    const ERROR     = LoggerInterface::LEVEL['ERROR'];
    const WARNING   = LoggerInterface::LEVEL['WARNING'];
    const NOTICE    = LoggerInterface::LEVEL['NOTICE'];
    const INFO      = LoggerInterface::LEVEL['INFO'];
    const DEBUG     = LoggerInterface::LEVEL['DEBUG'];
}
