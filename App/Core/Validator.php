<?php

namespace App\Core;

use App\Core\Exception\ValidatorException;

class Validator
{
  public static function lengthCorrect(string $string, int $min, int $max, string $error)
  {
    if (strlen($string) < $min || strlen($string) >  $max) {
      http_response_code(400);
      throw new ValidatorException($error);
    }
  }
}
