<?php

namespace App\Controller;

use App\Core\Exception\AuthenticationException;
use App\Core\Router;
use App\Core\Security;
use App\Model\HabitatComment;
use App\Model\ReportAnimal;
use App\Model\Schedule;
use App\Model\User;
use Exception;

class AuthController extends Controller
{

  public function index()
  {

    if (!Security::isLogged()) {
      Router::redirect('login');
    } else {
      if (Security::isAdmin()) {
        $reportAnimalRepo = new ReportAnimal();
        $habitatCommentRepo = new HabitatComment();

        $reportAnimalRepo->setLimit(5);
        $habitatCommentRepo->setLimit(5);

        $reportAnimals = $reportAnimalRepo->fetchReportAnimal('', '',   '', '');
        $habitatComments = $habitatCommentRepo->fetchHabitatsComment('', '', '');
        $famousAnimals = [];
        $this->show(
          'admin/dashboard/dashboard',
          [
            'reportAnimals' => $reportAnimals,
            'habitatComments' => $habitatComments,
            'famousAnimals' => $famousAnimals
          ]
        );
      }
      if (Security::isEmployee()) {

        Router::redirect('dashboard/alimentation-animaux');
      }
      if (Security::isVeterinary()) {
        Router::redirect('dashboard/rapport-animaux');
      }
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
        $user = new User();

        if ($user->login($email, $password)) {
          Router::redirect('dashboard');
        } else {
          $_SESSION['error'] = "adresse email ou mot de passe incorrecte";
        }
      } else {
        $_SESSION['error'] = "csrf token is invalid";
      }

      sleep(1);
    }

    $this->show('admin/login', ['error' => $error]);
  }

  public function logout()
  {
    $user = new User();
    $user->logout();

    session_destroy();

    Router::redirect();
    exit;
  }
}
