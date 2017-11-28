<?php
declare(strict_types = 1);

namespace App\Response;

use App\Main;
use Tools\Session\Session;

class Response
{
    const MESSAGE_KEY = 'message';
    const MESSAGE_TYPE = 'type';
    const MESSAGE_TEXT = 'text';

    const ALERT_CLASS = [
        'PRIMARY' => 'primary',
        'SECONDARY' => 'secondary',
        'SUCCESS' => 'success',
        'DANGER' => 'danger',
        'WARNING' => 'warning',
        'INFO' => 'info',
        'LIGHT' => 'light',
        'DARK' => 'dark'
    ];

    /**
     * @var Response
     */
    protected static $instance;

    /**
     * @var array
     */
    private $vars = [];

    /**
     * @return Response
     */
    public static function getInstance() : Response
    {
        if (null === static::$instance) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    /**
     * Response constructor.
     */
    protected function __construct()
    {
        $this->vars[self::MESSAGE_KEY] = Session::getInstance()[self::MESSAGE_KEY];
    }

    protected function __clone()
    {

    }

    /**
     * @param mixed $var
     * @param null $value
     */
    public function set($var, $value = null) : void
    {
        if (is_array($var)) {
            $this->vars = array_merge($this->vars, $var);
        } else {
            $this->vars[$var] = $value;
        }
    }

    /**
     * @param string $text
     * @param string $type
     */
    public function addMessage(string $text, string $type = 'info') : void
    {
        $message = [
            self::MESSAGE_TYPE => $type,
            self::MESSAGE_TEXT => $text
        ];

        $this->vars[self::MESSAGE_KEY][] = $message;
        $this->writeMessagesToSession();
    }


    private function writeMessagesToSession() : void
    {
        Session::getInstance()[self::MESSAGE_KEY] = $this->vars[self::MESSAGE_KEY];
    }

    public function clearMessages() : void
    {
        unset(Session::getInstance()[self::MESSAGE_KEY]);
        unset($this->vars[self::MESSAGE_KEY]);
    }

    /**
     * @param string $name
     * @param mixed $value
     */
    public function __set(string $name, $value) : void
    {
        $this->vars[$name] = $value;
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function __get(string $name)
    {
        return $this->vars[$name];
    }

    /**
     * @param AbstractOutput $output
     */
    public function render(AbstractOutput $output) : void
    {
        $output->loadData($this->vars);
        $output->printHeaders();
        echo $output->printPage();

        $this->clearMessages();
    }

}
