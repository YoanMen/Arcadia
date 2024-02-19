<?php

namespace App\Controller;

use App\Core\Router;
use App\Core\Security;
use App\Model\User;

class AuthController extends Controller
{

  public function index()
  {
    if (!Security::isLogged()) {
      Router::redirect('login');
    }

    if (Security::isAdmin()) {

      $this->show('admin/dashboard');
    } else {
      Router::redirect();
    }
  }

  public function login()
  {

    if (Security::isLogged()) {

      if (Security::isAdmin()) {

        Router::redirect('dashboard');
      } else {
        Router::redirect();
      }
    }

    $error = null;

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      $email = htmlspecialchars($_POST['email']);
      $password = htmlspecialchars($_POST['password']);

      if (Security::verifyCsrf($_POST['csrf_token'])) {
        $userRepository = new User();
        $user = $userRepository->findOneBy(['email' => $email]);

        if ($user && Security::verifyPassword($password, $user->getPassword())) {

          session_regenerate_id(true);
          $_SESSION['user'] = [
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'role' => $user->getRole(),
          ];

          Router::redirect('dashboard');
        } else {
          $error = "email or password is wrong";
        }
      } else {
        $error = "csrf token is invalid";
      }
    }


    $this->show('admin/login', ['error' => $error]);
  }

  public function logout()
  {
    $_SESSION = array();

    if (ini_get("session.use_cookies")) {

      $params = session_get_cookie_params();

      setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
      );
    }

    session_destroy();

    Router::redirect();
    exit;
  }
}
