<?php

namespace App\Controller;

use App\Model\CouchDB;
use App\Core\Router;
use App\Core\Security;
use App\Model\Animal;
use App\Model\HabitatComment;
use App\Model\ReportAnimal;
use App\Model\TryConnection;
use App\Model\User;

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
        $animalRepo = new Animal();

        $couchDb = new CouchDB();


        $reportAnimalRepo->setLimit(5);
        $habitatCommentRepo->setLimit(5);

        $reportAnimals = $reportAnimalRepo->fetchReportAnimal('', '',   '', '');
        $habitatComments = $habitatCommentRepo->fetchHabitatsComment('', '', '');

        $data = $couchDb->getFamousAnimals();

        if ($data) {
          foreach ($data as $animal) {

            $name = $animalRepo->fetchAnimalNameById($animal['_id']);
            $famousAnimals[] = [
              'id' => $animal['_id'],
              'click' => $animal['click'],
              'name' => $name[0] ?? ""
            ];
          }
        }

        $this->show(
          'admin/dashboard/dashboard',
          [
            'reportAnimals' => $reportAnimals,
            'habitatComments' => $habitatComments,
            'famousAnimals' => $famousAnimals ?? null
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
    $error = "adresse email ou mot de passe incorrecte";

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
      $email = trim($email);

      $password = htmlspecialchars($_POST['password']);

      if (Security::verifyCsrf($_POST['csrf_token'])) {

        $userModel = new User();
        $tryConnectionModel = new TryConnection();

        // check if user with this email exist
        $user = $userModel->findOneBy(["email" => $email]);

        if ($user) {

          // check if tryConnection exist for this user
          $tryConnection =  $tryConnectionModel->findByUserId($user->getId());

          // check if try connection count is not >= COUNT_CONNECTION
          if ($tryConnection && $tryConnection->getCount() >= COUNT_CONNECTION) {

            $error = "compte bloqué à cause d'une trop grande tentative de connection, contactez l'administrateur";
          } elseif ($userModel->login($email, $password)) {

            // delete try connection if count < COUNT_CONNECTION and password is correct
            if ($tryConnection) {
              $tryConnectionModel->delete(['user_id' => $user->getId()]);
            }

            Router::redirect('dashboard');
          } else {

            // add try connection count
            if ($tryConnection) {

              $count  =  $tryConnection->getCount() + 1;

              $tryConnectionModel->update(['count' => $count], $user->getId(), 'user_id');
              $number = (COUNT_CONNECTION + 1) - $count;
              $error = $number . ' tentative avant que le compte ne soit bloqué';
            } else {

              $tryConnectionModel->insert(['user_id' =>  $user->getId()]);
            }
          }
        }

        $_SESSION['error'] = $error;
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
