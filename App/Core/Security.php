<?php

namespace App\Core;

class Security
{

  public static function isLogged(): bool
  {
    return isset($_SESSION['user']);
  }

  public static function isEmployee(): bool
  {
    return isset($_SESSION['user']) && $_SESSION['user']['role'] === 'employee';
  }
  public static function isVeterinary(): bool
  {
    return isset($_SESSION['user']) && $_SESSION['user']['role'] === 'veterinary';
  }
  public static function isAdmin(): bool
  {
    return isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin';
  }

  public static function getUsername(): string|bool
  {
    return (isset($_SESSION['user']) && isset($_SESSION['user']['email'])) ? $_SESSION['user']['email'] : false;
  }
  public static function getCurrentUserId(): int|bool
  {
    return (isset($_SESSION['user']) && isset($_SESSION['user']['id'])) ? $_SESSION['user']['id'] : false;
  }
  public static function hashPassword($password): string
  {
    return password_hash($password, PASSWORD_DEFAULT);
  }

  public static function verifyPassword(string $password, string $ddbServer): bool
  {
    return password_verify($password, $ddbServer);
  }

  public static function verifyCsrf(string $token): bool
  {
    $token = htmlspecialchars($token);
    if ($_SESSION['csrf_token'] === $token) {
      return true;
    }

    return false;
  }
}
