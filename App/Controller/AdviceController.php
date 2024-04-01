<?php

namespace App\Controller;

use App\Core\Exception\ValidatorException;
use App\Core\Security;
use App\Core\Validator;
use App\Model\Advice;
use Exception;

class AdviceController extends Controller
{

  public function sendAdvice()
  {
    $csrf = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
    if (
      Security::verifyCsrf($csrf)
      && $_SERVER['REQUEST_METHOD'] ===  'POST'
    ) {
      try {
        $content = trim(file_get_contents("php://input"));
        $data = json_decode($content, true);
        $pseudo = htmlspecialchars($data['pseudo']);
        $message =  htmlspecialchars($data['message']);

        Validator::lengthCorrect($pseudo, 3, 20, 'Le nombres de caractère pour le pseudo dois être entre 3 et 20.');
        Validator::lengthCorrect($message, 3, 200, 'Le nombres de caractère pour le message dois être entre 3 et 200.');

        $adviceRepository = new Advice();
        $adviceRepository->insert(['pseudo' => $pseudo, 'advice' => $message]);

        http_response_code(201);
      } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
      }
    } else {
      http_response_code(401);
      echo json_encode(['error' => 'CSRF token is not valid']);
    }
  }

  public function getApprovedAdvices()
  {
    $csrf = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
    if (Security::verifyCsrf($csrf)) {

      try {
        $adviceRepository = new Advice();

        $advice = $adviceRepository->getApprovedAdvices();

        if (isset($advice)) {
          header('Content-Type: application/json');
          echo json_encode(['advices' => $advice]);
        } else {
          http_response_code(404);
          echo json_encode(['error' => 'Advice not found']);
        }
      } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
      }
    } else {
      http_response_code(401);
      echo json_encode(['error' => 'CSRF token is not valid']);
    }
  }
  public function getAdvice($request)
  {
    $csrf = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
    if (Security::verifyCsrf($csrf)) {

      try {
        $id  = filter_var($request['id'], FILTER_VALIDATE_INT);

        if (!is_int($id)) {
          http_response_code(400);
          echo json_encode(['error' => 'Is not a int']);
          return;
        }
        $adviceRepository = new Advice();
        $advice =  $adviceRepository->findOneBy(['id' => $id]);

        if (isset($advice)) {
          header('Content-Type: application/json');
          echo json_encode([
            'pseudo' => $advice->getPseudo(),
            'advice' => $advice->getAdvice()
          ]);
        } else {
          http_response_code(404);
          echo json_encode(['error' => 'Advice not found']);
        }
      } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
      }
    } else {
      http_response_code(401);
      echo json_encode(['error' => 'CSRF token is not valid']);
    }
  }


  public function updateAdvice()
  {
    $csrf = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';

    if (Security::verifyCsrf($csrf) && Security::isEmployee() && $_SERVER['REQUEST_METHOD'] == 'POST') {

      try {
        $content = trim(file_get_contents('php://input'));
        $data = json_decode($content, true);

        $id = htmlspecialchars($data['params']['id']);

        if (!is_int(intval($id))) {
          throw new ValidatorException('ID doit être un int');
        }
        $adviceRepo = new Advice();

        $advice = $adviceRepo->findOneBy(['id' => $id]);

        $current = $advice->getApproved();

        $adviceRepo->update(['approved' => !$current], $id);
        echo json_encode(['success' => 'l\'avis à été modifié']);
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

  public function getAdvices()
  {
    $csrf = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
    if (Security::verifyCsrf($csrf) && $_SERVER['REQUEST_METHOD'] === 'POST' && Security::isEmployee()) {
      try {

        $content = trim(file_get_contents('php://input'));
        $data = json_decode($content, true);

        // get all params
        $order = htmlspecialchars($data['params']['order']);
        $orderBy = htmlspecialchars($data['params']['orderBy']);
        $count = htmlspecialchars($data['params']['count']);

        $adviceRepo = new Advice();

        $adviceCount = $adviceRepo->count();

        $adviceRepo->setOffset($count);
        $advices = $adviceRepo->fetchAdvices($order, $orderBy);

        echo json_encode(['data' => $advices, 'totalCount' => $adviceCount]);
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
