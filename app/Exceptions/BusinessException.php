<?php

namespace App\Exceptions;

use Illuminate\Http\Response;
use Mi\L5Core\Exceptions\BaseException;
use Throwable;

class BusinessException extends BaseException
{
    public function __construct($message = "", $code = Response::HTTP_BAD_REQUEST, Throwable $previous = null)
    {
        parent::__construct(__('messages.' . $message), $code, $previous);
        $this->setMessageCode($message);
    }

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
}
