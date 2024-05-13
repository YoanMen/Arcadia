<?php

namespace App\Controller;

use App\Core\Exception\DatabaseException;
use App\Core\Router;
use App\Core\Security;
use App\Core\Validator;
use App\Model\Employee;
use App\Model\FoodAnimal;
use App\Model\Habitat;
use Exception;

class FoodAnimalController extends Controller
{
  private FoodAnimal $foodAnimal;

  public function __construct()
  {
    $this->foodAnimal = new FoodAnimal();
  }
  public function table()
  {
    if (Security::isLogged()) {

      if (Security::isEmployee() || Security::isVeterinary()) {

        $search = $_GET['search'] ?? '';
        $orderBy = $_GET['orderBy'] ?? 'Date';
        $order = $_GET['order'] ?? 'desc';
        $date = $_GET['date'] ?? '';

        try {
          $page = $_GET['page'] ?? 1;
          $currentPage = $page;
          $nbFoodAnimal = $this->foodAnimal->foodAnimalsCount($search, $date);

          $this->foodAnimal->setLimit(10);
          $totalPages = ceil($nbFoodAnimal / $this->foodAnimal->getLimit());
          $first = ($currentPage - 1) * $this->foodAnimal->getLimit();

          $this->foodAnimal->setOffset($first);

          $foodAnimals = $this->foodAnimal->fetchFoodAnimals($search, $date,   $order, $orderBy);

          $this->show('admin/food/table', [
            'params' => ['search' => $search, 'order' => $order, 'date' => $date],
            'foodAnimals' => $foodAnimals,
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

      if (Security::isEmployee() || Security::isVeterinary()) {
        $id = $request['id'];

        try {

          Validator::strIsInt($id);

          $foodAnimal = $this->foodAnimal->fetchFoodAnimalsByID($id);

          $this->show('admin/food/detail', [
            'foodAnimal' => $foodAnimal
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

  public function add()
  {
    if (Security::isLogged()) {

      if (Security::isEmployee()) {
        if ($_SERVER['REQUEST_METHOD'] === "POST") {
          $csrf = $_POST['csrf_token'] ?? '';
          if (Security::verifyCsrf($csrf)) {
            try {


              $userId = Security::getCurrentUserId();
              $animalId = htmlspecialchars($_POST['animal']);

              $food = htmlspecialchars($_POST['food']);
              $quantity = htmlspecialchars($_POST['quantity']);
              $date =  htmlspecialchars($_POST['date']);
              $time =  htmlspecialchars($_POST['time']);

              $food = ltrim($food, ' ');
              $food = strtolower($food);

              Validator::isNull($animalId, 'Animal ne peut pas être null');
              Validator::strIsInt($animalId);
              Validator::strIsInt($userId);
              Validator::strLengthCorrect($food, 3, 20);
              Validator::strIsFloat($quantity);
              Validator::strIsDateOrTime($date);
              Validator::strIsDateOrTime($time);

              $employee = new Employee();
              $employee->giveFood(
                $userId,
                $animalId,
                $food,
                $quantity,
                $time,
                $date
              );

              $_SESSION['success'] = 'Le rapport de nourrissage a été crée';
              Router::redirect('dashboard/alimentation-animaux');
            } catch (Exception $e) {
              $_SESSION['error'] =  $e->getMessage();
            }
          } else {

            $_SESSION['error'] = 'Clé CSRF non valide';
          }
        }

        $habitatRepo = new Habitat();
        $habitats = $habitatRepo->fetchAllHabitatsWithoutComment();


        $this->show('admin/food/add', [
          'animal_id' => $animalId ?? '',
          'food' => $food ?? '',
          'quantity' => $quantity ?? '',
          'date' => $date ?? '',
          'time' => $time ?? '',
          'habitats' => $habitats
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
