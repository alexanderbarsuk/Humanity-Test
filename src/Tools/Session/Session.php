<?php
declare(strict_types = 1);

namespace Tools\Session;

use App\Request;
use Tools\Log\Logger;

class Session implements \ArrayAccess
{
    const DURATION = 604800;
    const PATH = '/';

    /**
     * @var Session
     */
    protected static $instance;

    /**
     * @return Session
     */
    public static function getInstance() : Session
    {
        if (null === static::$instance) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    /**
     * Session constructor.
     */
    protected function __construct()
    {
        //$domain = Request::getInstance()->getHost();

        //ini_set('session.cookie_domain', '.'.$domain);
        //session_set_cookie_params(self::DURATION, self::PATH, "." . $domain);

        try {
            if (!session_start()) {
                throw new \Exception("Can't start session");
            }
        } catch (\Exception $exc) {
            echo 'session has fucked up';
        }
    }

    private function __clone()
    {
    }

    /**
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($_SESSION[$offset]);
    }

    /**
     * @param mixed $offset
     * @return null
     */
    public function offsetGet($offset)
    {
        return $_SESSION[$offset] ?? null;
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        $_SESSION[$offset] = $value;
    }

    /**
     * @param mixed $offset
     */
    public function offsetUnset($offset)
    {
        if(isset($_SESSION[$offset])) {
            unset($_SESSION[$offset]);
        }
    }
}