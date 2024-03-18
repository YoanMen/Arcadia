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
      $this->show('admin/login');
    } else {

      $this->show('admin/dashboard');
    }
  }

  public function login()
  {

    $error = null;

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
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
          $error = "adresse email ou mot de passe incorrecte";
        }
      } else {
        $error = "csrf token is invalid";
      }
    }

    sleep(1);
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
