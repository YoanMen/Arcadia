<?php

namespace App\Controller;

use App\Core\Exception\DatabaseException;
use App\Core\Exception\ValidatorException;
use App\Core\Router;
use App\Core\Security;
use App\Core\Validator;
use App\Model\Advice;
use App\Model\Employee;
use Exception;

class AdviceController extends Controller
{
  private Advice $advice;

  public function __construct()
  {
    $this->advice = new Advice();
  }
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

        Validator::strLengthCorrect($pseudo, 3, 20);
        Validator::strLengthCorrect($message, 3, 200);

        $this->advice->insert(['pseudo' => $pseudo, 'advice' => $message]);

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

  public function getApprovedAdvice($request)
  {
    $csrf = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
    if (Security::verifyCsrf($csrf)) {
      $id = $request['id'];
      try {
        $adviceRepository = new Advice();

        $count = $adviceRepository->approvedAdviceCount();
        $advice = $adviceRepository->getApprovedAdvice($id);

        header('Content-Type: application/json');
        echo json_encode([
          'totalCount' => $count,
          'advice' => boolval($advice) ? $advice : null
        ]);
      } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
      }
    } else {
      http_response_code(401);
      echo json_encode(['error' => 'CSRF token is not valid']);
    }
  }

  public function table()
  {
    if (Security::isLogged()) {

      if (Security::isEmployee()) {
        $search = $_GET['search'] ?? '';
        $orderBy = $_GET['orderBy'] ?? 'id';
        $order = $_GET['order'] ?? 'desc';

        try {
          $page = $_GET['page'] ?? 1;
          $currentPage = $page;

          $nbAdvices = $this->advice->adviceCount($search);

          $this->advice->setLimit(10);
          $totalPages = ceil($nbAdvices / $this->advice->getLimit());
          $first = ($currentPage - 1) * $this->advice->getLimit();

          $this->advice->setOffset($first);
          $advices = $this->advice->fetchAdvices($search, $order, $orderBy);

          $this->show('admin/advice/table', [
            'params' => ['search' => $search, 'order' => $order],
            'advices' => $advices,
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

      if (Security::isEmployee()) {
        $id = $request['id'];

        try {

          Validator::strIsInt($id);

          $advice = $this->advice->findOneBy(['id' => $id]);

          $this->show('admin/advice/detail', [
            'advice' => $advice
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

  public function updateAdvice()
  {

    if (Security::isEmployee()) {
      if ($_SERVER['REQUEST_METHOD'] === "PUT") {

        $content = trim(file_get_contents('php://input'));
        $data = json_decode($content, true);

        $id = htmlspecialchars($data['id']);
        $approved = htmlspecialchars($data['approved']);
        $csrf = htmlspecialchars($data['csrf_token']);


        Validator::strIsInt($id);

        $approved = boolval($approved);
        if (Security::verifyCsrf($csrf)) {
          try {

            $employee = new Employee();
            $employee->setAdvice($approved, $id);

            echo json_encode(['success' =>  'avis modifié']);
          } catch (Exception $e) {
            http_response_code(500);
            $_SESSION['error'] =  $e->getMessage();
            echo json_encode(['error' =>  $e->getMessage()]);
          }
        } else {

          $_SESSION['error'] = 'Clé CSRF non valide';
          http_response_code(500);
          echo json_encode(['error' => 'Clé CSRF non valide']);
        }
      }
    } else {
      http_response_code(401);
      echo json_encode(['error' => 'accès interdit']);
    }
  }
}
