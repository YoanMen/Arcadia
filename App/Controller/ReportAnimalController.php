<?php

namespace App\Controller;

use App\Core\Exception\DatabaseException;
use App\Core\Router;
use App\Core\Security;
use App\Core\Validator;
use App\Model\Habitat;
use App\Model\ReportAnimal;
use App\Model\Veterinary;
use Exception;

class ReportAnimalController extends Controller
{

  private ReportAnimal $reportAnimals;

  public function __construct()
  {
    $this->reportAnimals = new ReportAnimal();
  }
  public function table()
  {
    if (Security::isLogged()) {

      if (Security::isVeterinary() || Security::isAdmin()) {
        $search = $_GET['search'] ?? '';
        $orderBy = $_GET['orderBy'] ?? 'Date';
        $order = $_GET['order'] ?? 'desc';
        $date = $_GET['date'] ?? '';
        try {
          $page = $_GET['page'] ?? 1;
          $currentPage = $page;
          $nbFoodAnimal = $this->reportAnimals->fetchReportAnimalCount($search, $date);

          $this->reportAnimals->setLimit(10);
          $totalPages = ceil($nbFoodAnimal / $this->reportAnimals->getLimit());
          $first = ($currentPage - 1) * $this->reportAnimals->getLimit();

          $this->reportAnimals->setOffset($first);


          $reportAnimals = $this->reportAnimals->fetchReportAnimal($search, $date,   $order, $orderBy);

          $this->show('admin/report/table', [
            'params' => ['search' => $search, 'order' => $order, 'date' => $date],
            'reportAnimals' => $reportAnimals,
            'totalPages' => ($totalPages != 0) ?  $totalPages : 1,
            'currentPage' =>  $currentPage,
          ]);
        } catch (Exception $e) {
          throw new DatabaseException($e);
        }
      } else {
        $_SESSION['error'] = 'Vous n\'avez pas la permission';
        Router::redirect('dashboard');
      }
    } else {
      Router::redirect('login');
    }
  }


  public function detail($request)
  {
    if (Security::isLogged()) {

      if (Security::isAdmin() || Security::isVeterinary()) {
        $id = $request['id'];

        try {

          Validator::strIsInt($id);

          $reportAnimal = $this->reportAnimals->fetchReportAnimalByID($id);

          if ($reportAnimal) {

            $this->show('admin/report/detail', [
              'reportAnimal' => $reportAnimal
            ]);
          } else {
            $_SESSION['error'] = 'aucun rapport pour cette animal';
            Router::redirect('dashboard/rapport-animaux');
          }
        } catch (Exception $e) {
          throw new DatabaseException($e);
        }
      } else {
        $_SESSION['error'] = 'Vous n\'avez pas la permission';
        Router::redirect('dashboard');
      }
    } else {
      Router::redirect('login');
    }
  }


  public function add()
  {
    if (Security::isLogged()) {

      if (Security::isVeterinary()) {
        if ($_SERVER['REQUEST_METHOD'] === "POST") {
          $csrf = $_POST['csrf_token'] ?? '';
          if (Security::verifyCsrf($csrf)) {
            try {

              $userId = Security::getCurrentUserId();

              $animalId = htmlspecialchars($_POST['animal']);
              $food = htmlspecialchars($_POST['food']);
              $quantity = htmlspecialchars($_POST['quantity']);
              $details = htmlspecialchars($_POST['details']);
              $statut = htmlspecialchars($_POST['statut']);
              $date =  htmlspecialchars($_POST['date']);

              $food = trim($food);
              $food = strtolower($food);

              $statut = trim($statut);
              $statut = strtolower($statut);

              $details = ltrim($details);

              Validator::isNull($animalId, 'Animal ne peut pas être nulle');
              Validator::strIsInt($animalId, 'id animal n\'est pas valide');
              Validator::strIsInt($userId, 'id user n\'est pas valide');
              Validator::strLengthCorrect($statut, 1, 20);
              Validator::strLengthCorrect($food, 3, 60);
              Validator::strIsFloat($quantity);
              Validator::strIsDateOrTime($date);

              $veterinary = new Veterinary();

              $veterinary->reportAnimal(
                $userId,
                $animalId,
                $food,
                $quantity,
                $date,
                $details,
                $statut
              );

              $_SESSION['success'] = 'Le rapport sur l\'animal à été crée';
              Router::redirect('dashboard/rapport-animaux');
            } catch (Exception $e) {
              $_SESSION['error'] =  $e->getMessage();
            }
          } else {

            $_SESSION['error'] = 'Clé CSRF non valide';
          }
        }

        $habitatRepo = new Habitat();
        $habitats = $habitatRepo->fetchAllHabitatsWithoutComment();


        $this->show('admin/report/add', [
          'animal_id' => $animalId ?? '',
          'food' => $food ?? '',
          'quantity' => $quantity ?? '',
          'statut' => $statut ?? '',
          'details' => $details ?? '',
          'date' => $date ?? '',
          'habitats' => $habitats,

        ]);
      } else {
        $_SESSION['error'] = 'Vous n\'avez pas la permission';
        Router::redirect('dashboard');
      }
    } else {
      Router::redirect('login');
    }
  }
}
