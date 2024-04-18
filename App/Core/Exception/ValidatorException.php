<?php

namespace App\Core\Exception;

use Exception;
use Throwable;

class ValidatorException extends Exception
{

  public function insert($message, $code = 0, Throwable $previous = null)
  {
    parent::__construct($message, $code, $previous);
  }
}
