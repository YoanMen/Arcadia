<?php

namespace App\Controller;

use App\Core\Exception\DatabaseException;
use App\Core\Exception\ValidatorException;
use App\Core\Security;
use App\Core\Validator;
use App\Model\FoodAnimal;
use App\Model\Habitat;
use App\Model\User;
use Exception;

class FoodAnimalController extends Controller
{
  public function getFoods()
  {
    $csrf = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
    if (Security::verifyCsrf($csrf) && $_SERVER['REQUEST_METHOD'] === 'POST' && Security::isEmployee()) {
      try {

        $content = trim(file_get_contents('php://input'));
        $data = json_decode($content, true);

        // get all params
        $search = htmlspecialchars($data['params']['search']);
        $order = htmlspecialchars($data['params']['order']);
        $orderBy = htmlspecialchars($data['params']['orderBy']);
        $count = htmlspecialchars($data['params']['count']);

        $foodAnimalRepo = new FoodAnimal();
        $habitatRepo = new Habitat();
        $habitats = $habitatRepo->fetchAll(true);
        $foodCount = $foodAnimalRepo->foodAnimalsCount($search);
        $remainCount = $foodCount - $count;
        // check if remaining data
        if ($remainCount > 0) {
          $foodAnimalRepo->setOffset($count);

          $userRepo = new User();
          $user = $userRepo->findOneBy(['email' => Security::getUsername()]);
          $foods = $foodAnimalRepo->fetchFoodAnimalsByUser($search,  $user->getId(),  $order, $orderBy);
          echo json_encode(['data' => $foods, 'totalCount' => $foodCount, 'habitats' => $habitats]);
        } else {
          throw new DatabaseException('aucun résultat');
        }
      } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
      }
    } else {
      http_response_code(401);

      if (!Security::verifyCsrf($csrf)) {
        echo json_encode(['error' => 'CSRF token is not valid']);
      } else {
        echo json_encode(['error' => 'accès interdit']);
      }
    }
  }

  public function createFood()
  {
    $csrf = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? "";

    if (Security::verifyCsrf($csrf) && $_SERVER['REQUEST_METHOD'] === "POST" && Security::isEmployee()) {
      try {
        $content = trim(file_get_contents('php://input'));
        $data = json_decode($content, true);

        $userId = Security::getCurrentUserId();
        $animalId = htmlspecialchars($data['params']['animal_id']);
        $food = htmlspecialchars($data['params']['food']);
        $quantity = htmlspecialchars($data['params']['quantity']);
        $date =  htmlspecialchars($data['params']['date']);
        $time =  htmlspecialchars($data['params']['time']);

        $food = ltrim($food, ' ');
        $food = strtolower($food);
        $food = ucfirst($food);

        Validator::strIsInt($animalId);
        Validator::strIsInt($userId);
        Validator::strLengthCorrect($food, 3, 20);
        Validator::strIsFloat($quantity);
        Validator::strIsDateOrTime($date);
        Validator::strIsDateOrTime($time);

        // insert to table
        $foodRepo = new FoodAnimal();

        $foodRepo->insert([
          'userId' => $userId, 'animalId' => $animalId,
          'food' => $food, 'quantity' => $quantity, 'time' => $time,
          'date' => $date
        ]);
        echo json_encode(['success' => 'l/`animal à été crée']);
      } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
      }
    } else {
      http_response_code(401);

      if (!Security::verifyCsrf($csrf)) {
        echo json_encode(['error' => 'CSRF token is not valid']);
      } else {
        echo json_encode(['error' => 'accès interdit']);
      }
    }
  }
}
