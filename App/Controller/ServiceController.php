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
      Security::verifyCsrf($csrf) && !Security::isVeterinary() && $_SERVER['REQUEST_METHOD'] === 'DELETE'
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

  public function table()
  {
    if (Security::isLogged()) {
      if (Security::isAdmin() || Security::isEmployee()) {
        $search = $_GET['search'] ?? '';
        $orderBy = $_GET['orderBy'] ?? 'id';
        $order = $_GET['order'] ?? 'asc';

        try {
          $page = $_GET['page'] ?? 1;
          $currentPage = $page;

          $serviceRepo = new Service();
          $nbUsers = $serviceRepo->servicesCount($search);

          $serviceRepo->setLimit(10);
          $totalPage = ceil($nbUsers / $serviceRepo->getLimit());
          $first = ($currentPage - 1) * $serviceRepo->getLimit();

          $serviceRepo->setOffset($first);
          $data = $serviceRepo->fetchServices($search, $order, $orderBy);

          $this->show('admin/service/table', [
            'params' => ['search' => $search, 'order' => $order],
            'services' => $data,
            'totalPages' => ($totalPage != 0) ?  $totalPage : 1,
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


  public function add()
  {
    if (Security::isLogged()) {

      if (Security::isAdmin() || Security::isEmployee()) {
        if ($_SERVER['REQUEST_METHOD'] === "POST") {
          $csrf = $_POST['csrf_token'] ?? '';
          if (Security::verifyCsrf($csrf)) {
            try {
              $name =  htmlspecialchars($_POST['name']);
              $description = htmlspecialchars($_POST['description']);

              $name = trim($name);
              $description = trim($description);

              $name = strtolower($name);
              $name = ucfirst($name);

              Validator::strLengthCorrect($name, 3, 60);
              Validator::strWithoutSpecialCharacters($name, 'Le nom ne doit pas contenir de caractère spéciales');
              Validator::strMinLengthCorrect($description, 10);

              $serviceRepo = new Service();

              $service = $serviceRepo->findOneBy(['name' => $name]);

              if ($service) {
                throw new ValidatorException('un service avec ce nom existe déjà');
              }

              $serviceRepo->insert(['name' => $name, 'description' => $description]);

              $_SESSION['success'] = 'Le service ' . $name . ' à été crée';
              Router::redirect('dashboard/services');
            } catch (Exception $e) {
              $_SESSION['error'] =  $e->getMessage();
            }
          } else {

            $_SESSION['error'] = 'Clé CSRF non valide';
          }
        }

        $this->show('admin/service/add', [
          'name' => $name ?? '',
          'description' => $description ?? '',

        ]);
      } else {
        $_SESSION['error'] = 'Vous n\'avez pas la permission';
        Router::redirect('dashboard');
      }
    } else {
      Router::redirect('login');
    }
  }

  public function edit($request)
  {
    if (Security::isLogged()) {
      if (Security::isAdmin() || Security::isEmployee()) {
        if ($_SERVER['REQUEST_METHOD'] === "POST") {
          $csrf = $_POST['csrf_token'] ?? '';
          if (Security::verifyCsrf($csrf)) {
            try {

              $id = htmlspecialchars($request['id']);
              $name = htmlspecialchars($_POST['name']);
              $description = htmlspecialchars($_POST['description']);


              $name = trim($name);
              $description = ltrim($description, ' ');

              $name = strtolower($name);
              $name = ucfirst($name);

              Validator::strIsInt($id);
              Validator::strLengthCorrect($name, 3, 60);
              Validator::strWithoutSpecialCharacters($name, 'Le nom ne doit pas contenir de caractère spéciales');
              Validator::strMinLengthCorrect($description, 10);

              $serviceRepo = new Service();

              $service = $serviceRepo->findOneBy(['name' => $name]);

              if ($service && $service->getId() != $id) {
                throw new ValidatorException('un service avec ce nom existe déjà');
              }

              $serviceRepo->update(['name' => $name, 'description' => $description], $id);

              $_SESSION['success'] =  'Le service ' . $name . ' à été modifié';

              Router::redirect('dashboard/services');
            } catch (Exception $e) {
              $_SESSION['error'] =  $e->getMessage();
            }
          } else {

            $_SESSION['error'] = 'Clé CSRF non valide';
          }
        }

        $serviceRepo = new Service();
        $service = $serviceRepo->findOneBy(['id' => $request['id']]);

        $this->show('admin/service/edit', [
          'service' => $service,
        ]);
      } else {
        $_SESSION['error'] = 'Vous n\'avez pas la permission';
        Router::redirect('dashboard');
      }
    } else {
      Router::redirect('login');
    }
  }
  public function delete($request)
  {
    if ((Security::isAdmin() || Security::isEmployee()) && $_SERVER['REQUEST_METHOD'] === "POST") {
      $csrf = $_POST['csrf_token'] ?? '';
      if (Security::verifyCsrf($csrf)) {
        try {
          $id =  htmlspecialchars($request['id']);
          Validator::strIsInt($id);

          $serviceRepo = new Service();

          $serviceRepo->delete(['id' => $id]);

          $_SESSION['success'] = 'Le service à été supprimé';
        } catch (Exception $e) {
          $_SESSION['error'] =  $e->getMessage();
        }
      } else {
        $_SESSION['error'] = 'Clé CSRF pas valide';
      }
      Router::redirect('dashboard/services');
    } else {
      $_SESSION['error'] = 'Vous n\'avez pas la permission';
      Router::redirect('dashboard');
    }
  }
}
