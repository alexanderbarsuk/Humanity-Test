<?php
declare(strict_types = 1);

namespace Tools\Log;

use Tools\Log\Storage\AbstractStorage;
use Tools\Log\Exception\InvalidArgumentException;
use Tools\Log\Exception\UndefinedStorageException;

class Logger implements LoggerInterface
{
    /**
     * @var string
     */
    private $template = '{date} {level} {message}';

    /**
     * @var string
     */
    private $dateFormat = 'Y-m-d H:i:s';

    /**
     * @var AbstractStorage[]
     */
    private $storages = [];

    /**
     * @param AbstractStorage $storage
     * @return LoggerInterface
     */
    public function addStorage(AbstractStorage $storage) : LoggerInterface
    {
        $this->storages[] = $storage;
        return $this;
    }

    /**
     * Clear storage
     */
    public function clearStorages() : void
    {
        $this->storages = [];
    }

    /**
     * @param string $template
     * @return LoggerInterface
     */
    public function setTemplate(string $template) : LoggerInterface
    {
        $this->template = $template;
        return $this;
    }

    /**
     * @param string $format
     * @return LoggerInterface
     */
    public function setDateFormat(string $format) : LoggerInterface
    {
        $this->dateFormat = $format;
        return $this;
    }

    /**
     *
     * @param string $level should be an alement from LoggerInterface::LEVEL const
     * @param string $message
     * @param array $context
     * @throws InvalidArgumentException
     * @throws UndefinedStorageException
     * @return void
     */
    public function log(string $level, string $message, array $context = []) : void
    {
        if (count($this->storages) == 0) {
            throw new UndefinedStorageException("Please add one of available storages");
        }

        if (isset((LoggerInterface::LEVEL)[strtoupper($level)])) {
            foreach ($this->storages as $storage){
                $this->storeMessage($storage, $this->formatWithTemplate($this->interpolate($message, $context), $level));
            }
        } else {
            throw new InvalidArgumentException("Log level does not exist in current implementation");
        }
    }

    /**
     * @param AbstractStorage $storage
     * @param string $message
     */
    private function storeMessage(AbstractStorage $storage, string $message) : void
    {
        $storage->store($message);
    }

    /**
     * Interpolates context values into the message placeholders.
     *
     * @param $message
     * @param array $context
     * @return string
     */
    protected function interpolate(string $message, array $context = array()) : string
    {
        $replace = array();
        foreach ($context as $key => $val) {
            if (!is_array($val) && (!is_object($val) || method_exists($val, '__toString'))) {
                $replace['{' . $key . '}'] = $val;
            }
        }

        return strtr($message, $replace);
    }

    protected function formatWithTemplate(string $message, string $level) : string
    {
        return trim(strtr($this->template, [
                '{date}' => $this->getDate(),
                '{level}' => $level,
                '{message}' => $message,
            ])) . PHP_EOL;
    }

    /**
     * @return string
     */
    private function getDate() : string
    {
        return (new \DateTime())->format($this->dateFormat);
    }

    /**
     * System is unusable.
     *
     * @param string $message
     * @param array $context
     * @return void
     */
    public function emergency(string $message, array $context = array()) : void
    {
        $this->log(LoggerInterface::LEVEL['EMERGENCY'], $message, $context);
    }

    /**
     * Action must be taken immediately.
     *
     * Example: Entire website down, database unavailable, etc. This should
     * trigger the SMS alerts and wake you up.
     *
     * @param string $message
     * @param array $context
     * @return void
     */
    public function alert(string $message, array $context = array()) : void
    {
        $this->log(LoggerInterface::LEVEL['ALERT'], $message, $context);
    }

    /**
     * Critical conditions.
     *
     * Example: Application component unavailable, unexpected Exception.
     *
     * @param string $message
     * @param array $context
     * @return void
     */
    public function critical(string $message, array $context = array()) : void
    {
        $this->log(LoggerInterface::LEVEL['CRITICAL'], $message, $context);
    }

    /**
     * Runtime errors that do not require immediate action but should typically
     * be logged and monitored.
     *
     * @param string $message
     * @param array $context
     * @return void
     */
    public function error(string $message, array $context = array()) : void
    {
        $this->log(LoggerInterface::LEVEL['ERROR'], $message, $context);
    }

    /**
     * Exceptional occurrences that are not errors.
     *
     * Example: Use of deprecated APIs, poor use of an API, undesirable things
     * that are not necessarily wrong.
     *
     * @param string $message
     * @param array $context
     * @return void
     */
    public function warning(string $message, array $context = array()) : void
    {
        $this->log(LoggerInterface::LEVEL['WARNING'], $message, $context);
    }

    /**
     * Normal but significant events.
     *
     * @param string $message
     * @param array $context
     * @return void
     */
    public function notice(string $message, array $context = array()) : void
    {
        $this->log(LoggerInterface::LEVEL['NOTICE'], $message, $context);
    }

    /**
     * Interesting events.
     *
     * Example: User logs in, SQL logs.
     *
     * @param string $message
     * @param array $context
     * @return void
     */
    public function info(string $message, array $context = array()) : void
    {
        $this->log(LoggerInterface::LEVEL['INFO'], $message, $context);
    }

    /**
     * Detailed debug information.
     *
     * @param string $message
     * @param array $context
     * @return void
     */
    public function debug(string $message, array $context = array()) : void
    {
        $this->log(LoggerInterface::LEVEL['DEBUG'], $message, $context);
    }

}
