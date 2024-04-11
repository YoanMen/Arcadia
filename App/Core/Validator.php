<?php

namespace App\Core;

use App\Core\Exception\ValidatorException;

class Validator
{

  public static function isNull($variable, $error = 'La valeur est nulle')
  {
    if (!$variable) {
      http_response_code(400);
      throw new ValidatorException($error);
    }
  }
  public static function strLengthCorrect(
    string $string,
    int $min,
    int $max,
    string $error = 'La longueur du texte n\'est pas valide'
  ) {
    if (strlen($string) < $min || strlen($string) >  $max) {
      http_response_code(400);
      throw new ValidatorException($error);
    }
  }
  public static function strMinLengthCorrect(
    string $string,
    int $min,
    string $error = 'La longueur minimum du texte n\'est pas valide'
  ) {
    if (strlen($string) < $min) {
      http_response_code(400);
      throw new ValidatorException($error);
    }
  }

  public static function strIsInt(
    string $string,
    string $error = 'Ce n\'est pas un int'
  ) {
    if (!ctype_digit($string)) {
      http_response_code(400);
      throw new ValidatorException($error);
    }
  }


  public static function strIsFloat(
    string $string,
    string $error = 'Ce n\'est pas un float'
  ) {
    $float = floatval($string);

    if (!is_float($float)) {
      http_response_code(400);
      throw new ValidatorException($error);
    }
  }

  public static function strIsDateOrTime(string $string, string $error = 'Ce n\'est pas un une date/heure valide')
  {
    if (!strtotime($string)) {
      http_response_code(400);
      throw new ValidatorException($error);
    }
  }

  public static function strIsValideEmail(string $string)
  {
    if (!filter_var($string, FILTER_VALIDATE_EMAIL)) {
      http_response_code(400);
      throw new ValidatorException('Adresse email non valide');
    }
  }

  public static function strIsValidRole(string $string)
  {
    if ($string !== 'employee' && $string !== 'veterinary') {
      http_response_code(400);
      throw new ValidatorException('Le nom du rôle n\'est pas valide');
    }
  }

  public static function strWithoutSpecialCharacters(string $string, string $error = 'Le text ne doit pas contenir de caractère spéciales')
  {
    if (preg_match('/[!@#$%^&*()_+\-=\[\]{};\'\\:"|,.<>\/?]+/', $string)) {
      http_response_code(400);
      throw new ValidatorException($error);
    }
  }
}
