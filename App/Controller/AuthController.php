<?php

namespace App\Controller;

use App\Core\Exception\AuthenticationException;
use App\Core\Router;
use App\Core\Security;
use App\Model\Schedule;
use App\Model\User;
use Exception;

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
      $email = trim($email);

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


  public function getRole()
  {
    if (Security::isLogged() && $_SERVER['REQUEST_METHOD'] === 'GET') {


      switch (Security::getRole()) {
        case 'employee':
          echo json_encode(['role' => 'employee']);
          break;
        case 'veterinary':
          echo json_encode(['role' => 'veterinary']);
          break;
        case 'admin':
          echo json_encode(['role' => 'admin']);
          break;
        default:
          throw new AuthenticationException('le role n\'est pas valide');
      }
    } else {
      http_response_code(401);
      throw new AuthenticationException('Permission refusé');
    }
  }
  public function loadDashboardPage()
  {
    if (Security::isLogged() && $_SERVER['REQUEST_METHOD'] === 'GET') {
      header('Content-Type: text/html');

      switch (Security::getRole()) {
        case 'employee':
          require_once '../App/View/partials/admin/menu/_service.php';
          break;
        case 'veterinary':
          # code...
          break;
        case 'admin':
          require_once '../App/View/partials/admin/menu/_dashboard.php';
          break;
        default:
          throw new AuthenticationException('le role n\'est pas valide');
      }
    } else {
      http_response_code(401);
      throw new AuthenticationException('Permission refusé');
    }
  }

  public function loadUserPage()
  {
    if (Security::isAdmin() && $_SERVER['REQUEST_METHOD'] === 'GET') {
      header('Content-Type: text/html');

      require_once '../App/View/partials/admin/menu/_user.php';
    } else {
      http_response_code(401);
      throw new AuthenticationException('Permission refusé');
    }
  }

  public function loadHabitatPage()
  {
    if (Security::isAdmin() && $_SERVER['REQUEST_METHOD'] === 'GET') {
      header('Content-Type: text/html');

      include_once "../App/View/partials/admin/menu/_habitat.php";
    } else {
      http_response_code(401);
      throw new AuthenticationException('Permission refusé');
    }
  }

  public function loadAnimalPage()
  {
    if (Security::isAdmin() && $_SERVER['REQUEST_METHOD'] === 'GET') {
      header('Content-Type: text/html');

      include_once "../App/View/partials/admin/menu/_animal.php";
    } else {
      http_response_code(401);
      throw new AuthenticationException('Permission refusé');
    }
  }

  public function loadServicePage()
  {
    if (
      Security::isAdmin() && $_SERVER['REQUEST_METHOD'] === 'GET' ||
      Security::isEmployee() && $_SERVER['REQUEST_METHOD'] === 'GET'
    ) {
      header('Content-Type: text/html');

      include_once "../App/View/partials/admin/menu/_service.php";
    } else {
      http_response_code(401);
      throw new AuthenticationException('Permission refusé');
    }
  }
  public function loadSchedulePage()
  {
    if (
      Security::isAdmin() && $_SERVER['REQUEST_METHOD'] === 'GET'
    ) {
      header('Content-Type: text/html');
      $schedulesRepository = new Schedule();
      $schedules = $schedulesRepository->fetchAll();


      include_once "../App/View/partials/admin/menu/_schedule.php";
    } else {
      http_response_code(401);
      throw new AuthenticationException('Permission refusé');
    }
  }
  public function loadAdvicePage()
  {
    if (
      Security::isEmployee() && $_SERVER['REQUEST_METHOD'] === 'GET'
    ) {
      header('Content-Type: text/html');


      include_once "../App/View/partials/admin/menu/_advice.php";
    } else {
      http_response_code(401);
      throw new AuthenticationException('Permission refusé');
    }
  }

  public function loadFoodAnimalPage()
  {
    if (Security::isEmployee() && $_SERVER['REQUEST_METHOD'] == 'GET') {
      header('Content-Type: text/html');

      include_once '../App/View/partials/admin/menu/_foodAnimal.php';
    }
  }

  public function loadReportAnimalPage()
  {
  }

  public function loadCommentHabitatPage()
  {
  }
}
