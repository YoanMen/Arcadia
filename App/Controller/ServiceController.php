<?php

namespace App\Controller;

use App\Core\Exception\DatabaseException;
use App\Core\Exception\ValidatorException;
use App\Core\Router;
use App\Core\Security;
use App\Core\Validator;
use App\Model\Service;
use Exception;

class ServiceController extends Controller
{
  public function index()
  {
    $currentPage = 0;

    if (isset($_GET['page'])) {
      $getPage = filter_var($_GET['page'], FILTER_VALIDATE_INT);
      if (is_int($getPage) && $getPage > 1) {
        $currentPage = $getPage - 1;
      }
    }

    $serviceRepository = new Service();
    $nbServices = $serviceRepository->count();
    $serviceRepository->setLimit(10);
    $totalPages = ceil($nbServices / $serviceRepository->getLimit());
    $first = $currentPage * $serviceRepository->getLimit();
    $serviceRepository->setOffset($first);
    $services  = $serviceRepository->fetchAll();


    $this->show('service', [
      'services' => $services,
      'currentPage' => $currentPage + 1,
      'totalPages' => $totalPages
    ]);
  }

  public function showService($request)
  {
    try {
      $name =  htmlspecialchars($request['name']);
      $name = str_replace('-', ' ', $name);

      $serviceRepository = new Service();
      $service = $serviceRepository->findOneBy(['name' => $name]);

      if (isset($service)) {
        $this->show('serviceDetails', [
          'service' => $service
        ]);
      } else {
        throw new DatabaseException('Service not exist');
      }
    } catch (Exception $e) {
      Router::redirect('/error');
    }
  }

  public function getServices()
  {
    $csrf = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
    if (
      Security::verifyCsrf($csrf) && $_SERVER['REQUEST_METHOD'] === 'POST' && Security::isEmployee()
      || Security::verifyCsrf($csrf) && $_SERVER['REQUEST_METHOD'] === 'POST' && Security::isAdmin()
    ) {
      try {

        $content = trim(file_get_contents('php://input'));
        $data = json_decode($content, true);


        // get all params
        $search = htmlspecialchars($data['params']['search']);
        $order = htmlspecialchars($data['params']['order']);
        $orderBy = htmlspecialchars($data['params']['orderBy']);
        $count = htmlspecialchars($data['params']['count']);

        $serviceRepo = new Service();

        $serviceCount = $serviceRepo->servicesCount($search);
        $remainCount = $serviceCount - $count;


        // check if remaining data
        if ($remainCount > 0) {
          $serviceRepo->setOffset($count);
          $habitatsComment = $serviceRepo->fetchServices($search, $order, $orderBy);

          echo json_encode(['data' => $habitatsComment, 'totalCount' => $serviceCount]);
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

  public function createService()
  {
    $csrf = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';

    if (
      Security::verifyCsrf($csrf) && Security::isEmployee() && $_SERVER['REQUEST_METHOD'] === 'POST'
      || Security::verifyCsrf($csrf) && Security::isAdmin() && $_SERVER['REQUEST_METHOD'] === 'POST'
    ) {

      try {
        $content = trim(file_get_contents('php://input'));
        $data = json_decode($content, true);

        $name = htmlspecialchars($data['params']['name']);
        $description = htmlspecialchars($data['params']['description']);

        $name = ltrim($name, ' ');
        $description = ltrim($description, ' ');

        $name = ucfirst($name);

        Validator::strLengthCorrect($name, 3, 60);
        Validator::strMinLengthCorrect($description, 10);

        $serviceRepo = new Service();

        $serviceRepo->insert(['name' => $name, 'description' => $description]);
        echo json_encode(['success' => 'le service à été crée']);
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
  public function updateService()
  {
    $csrf = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';

    if (
      Security::verifyCsrf($csrf) && Security::isEmployee() && $_SERVER['REQUEST_METHOD'] === 'POST'
      || Security::verifyCsrf($csrf) && Security::isAdmin() && $_SERVER['REQUEST_METHOD'] === 'POST'
    ) {

      try {
        $content = trim(file_get_contents('php://input'));
        $data = json_decode($content, true);

        $id = htmlspecialchars($data['params']['id']);
        $name = htmlspecialchars($data['params']['name']);
        $description = htmlspecialchars($data['params']['description']);

        $name = ltrim($name, ' ');
        $description = ltrim($description, ' ');

        $name = ucfirst($name);

        Validator::strIsInt($id);
        Validator::strLengthCorrect($name, 3, 60);
        Validator::strMinLengthCorrect($description, 10);

        $serviceRepo = new Service();

        $habitat = $serviceRepo->findOneBy(['name' => $name]);
        if ($habitat && $habitat->getId() != $id) {
          throw new ValidatorException('un service avec ce nom existe déjà');
        }

        $serviceRepo->update(['name' => $name, 'description' => $description], $id);
        echo json_encode(['success' => 'le service à été modifié']);
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

  public function deleteService()
  {
    $csrf = $_SERVER['HTTP_X_CSRF_TOKEN'];

    if (
      Security::verifyCsrf($csrf) && Security::isEmployee() && $_SERVER['REQUEST_METHOD'] === 'DELETE'
      || Security::verifyCsrf($csrf) && Security::isAdmin() && $_SERVER['REQUEST_METHOD'] === 'DELETE'
    ) {

      try {
        $content = trim(file_get_contents('php://input'));
        $data = json_decode($content, true);

        $id = htmlspecialchars($data['params']['id']);

        $serviceRepo = new Service();
        $serviceRepo->delete(['id' => $id]);

        echo json_encode(['success' => 'le service à été supprimé']);
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
