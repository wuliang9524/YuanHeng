<?php

namespace Logan\Yuanheng\exceptions;

use RuntimeException;

class InitRuntimeException extends RuntimeException
{
    protected $code;

    protected $message;

    public function __construct(array $params)
    {
        if (!is_array($params)) {
            return;
        }

        // 参数数组中存在相应key值,则更新成员变量
        if (array_key_exists('code', $params)) {
            $this->code = $params['code'];
        }

        if (array_key_exists('msg', $params)) {
            $this->message = $params['msg'];
        }
    }
}
