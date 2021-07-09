<?php

// memo: https://learnku.com/articles/5657/laravel-exceptions-exception-and-error-handling

namespace Sakura\Lib;

use Exception as BaseException;

class Exception extends BaseException
{
  public function __construct($message, $code = 0)
  {
    parent::__construct($message, $code);
  }
}
