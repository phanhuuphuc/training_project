<?php

namespace Mi\L5Core\Exceptions;

use Exception;

class BaseException extends Exception
{
    /**
     * @var int
     */
    protected $code = 400;

    /**
     * @var string
     */
    protected $messageCode = null;

    /**
     * Set the message code
     *
     * @param string $code
     * @return self
     */
    public function setMessageCode(string $code)
    {
        $this->messageCode = $code;

        return $this;
    }

    /**
     * Get the message code
     *
     * @return string
     */
    public function getMessageCode()
    {
        return $this->messageCode;
    }

    /**
     * Create new exception instance with code
     *
     * @return self
     */
    public static function code($code, $args = [], $statusCode = 400)
    {
        return (new static(__('messages.' . $code, $args), $statusCode))->setMessageCode($code);
    }
}
