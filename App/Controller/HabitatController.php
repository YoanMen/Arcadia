<?php

namespace App\Controller;

use App\Core\Exception\DatabaseException;
use App\Core\Exception\ValidatorException;
use App\Core\Router;
use App\Core\Security;
use App\Core\UploadFile;
use App\Core\Validator;
use App\Model\Admin;
use App\Model\Animal;
use App\Model\Habitat;
use App\Model\Image;
use Exception;

class HabitatController extends Controller
{

  private Habitat $habitat;

  public function __construct()
  {
    $this->habitat = new Habitat();
  }
  // Page showing all habitats
  public function index()
  {
    $page = $_GET['page'] ?? 1;
    $currentPage = $page;

    // initialize data for habitat page
    $nbHabitats = $this->habitat->count();
    $this->habitat->setLimit(10);
    $totalPages = ceil($nbHabitats / $this->habitat->getLimit());
    $first = ($currentPage - 1) * $this->habitat->getLimit();
    $this->habitat->setOffset($first);
    $habitats = $this->habitat->fetchAll();

    // search image for all habitat
    if ($habitats) {
      foreach ($habitats as $habitat) {
        $habitat->findImages();
      }
    }

    $this->show('habitat', [
      'habitats' => $habitats,
      'totalPages' => ($totalPages != 0) ?  $totalPages : 1,
      'currentPage' =>  $currentPage,
    ]);
  }

  // show habitat detail
  public function showHabitat($request)
  {
    try {

      $page = $_GET['page'] ?? 1;
      $currentPage = $page;

      $name = htmlspecialchars($request['name']);
      $name = str_replace('-', ' ', $name);

      $animalRepository = new Animal();

      $habitat = $this->habitat->findOneBy(['name' => $name]);

      if (!$habitat) {
        throw new DatabaseException('Habitat not exist');
      }

      // find image for habitat
      $habitat->findImages();

      // find all animals and images corresponding habitat

      $nbAnimals = $animalRepository->count(['habitatId' => $habitat->getId()]);

      $animalRepository->setLimit(10);
      $totalPages = ceil($nbAnimals / $animalRepository->getLimit());
      $first = ($currentPage - 1) * $animalRepository->getLimit();

      $animalRepository->setOffset($first);
      $animals = $animalRepository->find(['habitatId' => $habitat->getId()]);

      if ($animals) {
        foreach ($animals as $animal) {
          $animal->findImages();
        }
      }

      $this->show('habitatDetails', [
        'habitat' => $habitat,
        'animals' => $animals,
        'totalPages' => ($totalPages != 0) ?  $totalPages : 1,
        'currentPage' =>  $currentPage,
      ]);
    } catch (Exception $e) {
      Router::redirect('error');
    }
  }

  // fetch delete image
  public function  deleteImage()
  {
    if (Security::isAdmin() && $_SERVER['REQUEST_METHOD'] === "DELETE") {
      $content = trim(file_get_contents('php://input'));
      $data = json_decode($content, true);

      $csrf = htmlspecialchars($data['csrf']);

      if (Security::verifyCsrf($csrf)) {

        try {
          $id = htmlspecialchars($data['id']);

          $image = new Image();
          $image->deleteImage($id);
        } catch (Exception $e) {
          http_response_code(500);
          $_SESSION['error'] = $e->getMessage();

          header('content-type:application/json');
          echo json_encode(['error' => $e->getMessage()]);
        }
      } else {
        http_response_code(401);
        header('content-type:application/json');
        echo json_encode(['error' => 'Clé CSRF non valide']);
      }
    } else {
      http_response_code(401);
      header('content-type:application/json');
      echo json_encode(['error' => 'Vous n\'avez pas la permission']);
    }
  }

  public function  uploadImage()
  {
    if (Security::isAdmin() && $_SERVER['REQUEST_METHOD'] === "POST") {


      $csrf = htmlspecialchars($_POST['csrf']);
      if (Security::verifyCsrf($csrf)) {

        try {
          $id = htmlspecialchars($_POST['id']);

          Validator::strIsInt($id);

          $path = UploadFile::upload();
          $imageRepo = new Image();

          $imageRepo->insert(['path' => $path]);
          $image = $imageRepo->findOneBy(['path' => $path]);

          $this->habitat->insertImage($id, $image->getId());

          $_SESSION['success'] = 'Image ajouté';
          header('content-type:application/json');
          echo json_encode(['path' => $image->getPath(), 'id' =>  $image->getId()]);
        } catch (Exception $e) {
          http_response_code(500);
          $_SESSION['error'] = $e->getMessage();
          header('content-type:application/json');
          echo json_encode(['error' => $e->getMessage()]);
        }
      } else {
        http_response_code(401);
        $_SESSION['error'] = 'Clé CSRF non valide';
        header('content-type:application/json');
        echo json_encode(['error' => 'Clé CSRF non valide']);
      }
    } else {
      http_response_code(401);
      header('content-type:application/json');
      echo json_encode(['error' => 'Vous n\'avez pas la permission']);
    }
  }


  public function table()
  {
    if (Security::isLogged()) {

      if (Security::isAdmin()) {
        $search = $_GET['search'] ?? '';
        $orderBy = $_GET['orderBy'] ?? 'id';
        $order = $_GET['order'] ?? 'asc';

        try {
          $page = $_GET['page'] ?? 1;
          $currentPage = $page;

          $nbUsers = $this->habitat->habitatsCount($search);

          $this->habitat->setLimit(10);
          $totalPage = ceil($nbUsers / $this->habitat->getLimit());
          $first = ($currentPage - 1) * $this->habitat->getLimit();

          $this->habitat->setOffset($first);
          $data = $this->habitat->fetchHabitats($search, $order, $orderBy);

          $this->show('admin/habitat/table', [
            'params' => ['search' => $search, 'order' => $order],
            'habitats' => $data,
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

      if (Security::isAdmin()) {
        if ($_SERVER['REQUEST_METHOD'] === "POST") {
          $csrf = $_POST['csrf_token'] ?? '';
          if (Security::verifyCsrf($csrf)) {
            try {
              $name =  htmlspecialchars($_POST['name']);
              $description = htmlspecialchars($_POST['description']);

              $name = trim($name);
              $description = ltrim($description, ' ');

              $name = ucfirst($name);
              $description = ucfirst($description);

              Validator::strLengthCorrect($name, 3, 60);
              Validator::strWithoutSpecialCharacters($name, 'Le nom ne doit pas contenir de caractère spéciales');
              Validator::strMinLengthCorrect($description, 10);

              $admin = new Admin();
              $admin->createHabitat($name, $description);

              $_SESSION['success'] =  'L\'habitat ' . $name . ' à été crée';
              Router::redirect('dashboard/habitats');
            } catch (Exception $e) {
              $_SESSION['error'] =  $e->getMessage();
            }
          } else {

            $_SESSION['error'] = 'Clé CSRF non valide';
          }
        }
        $this->show('admin/habitat/add', [
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
      if (Security::isAdmin()) {
        if ($_SERVER['REQUEST_METHOD'] === "POST") {
          $csrf = $_POST['csrf_token'] ?? '';
          if (Security::verifyCsrf($csrf)) {
            try {

              $id = htmlspecialchars($request['id']);
              $name = htmlspecialchars($_POST['name']);
              $description = htmlspecialchars($_POST['description']);

              $name = trim($name);
              $description = trim($description);

              $name = ucfirst($name);

              Validator::strIsInt($id);
              Validator::strLengthCorrect($name, 3, 60);
              Validator::strWithoutSpecialCharacters($name);
              Validator::strMinLengthCorrect($description, 10);


              $habitat = $this->habitat->findOneBy(['name' => $name]);
              if ($habitat && $habitat->getId() != $id) {
                throw new ValidatorException('un habitat avec ce nom existe déjà');
              }

              $admin = new Admin();
              $admin->updateHabitat($id, $name, $description);

              $_SESSION['success'] =  'L\'habitat ' . $name . ' à été modifié';

              Router::redirect('dashboard/habitats');
            } catch (Exception $e) {
              $_SESSION['error'] =  $e->getMessage();
            }
          } else {

            $_SESSION['error'] = 'Clé CSRF non valide';
          }
        }

        $habitat = $this->habitat->findOneBy(['id' => $request['id']]);
        $images = $this->habitat->fetchImages($request['id']);
        $this->show('admin/habitat/edit', [
          'habitat' => $habitat,
          'images' => $images
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
    if (Security::isAdmin() && $_SERVER['REQUEST_METHOD'] === "POST") {
      $csrf = $_POST['csrf_token'] ?? '';
      if (Security::verifyCsrf($csrf)) {
        try {
          $id =  htmlspecialchars($request['id']);
          Validator::strIsInt($id);

          $admin = new Admin();
          $admin->deleteHabitat($id);

          $_SESSION['success'] = 'l\'habitat à été supprimé';
        } catch (Exception $e) {
          $_SESSION['error'] =  $e->getMessage();
        }
      } else {
        $_SESSION['error'] = 'Clé CSRF pas valide';
      }
      Router::redirect('dashboard/habitats');
    } else {
      $_SESSION['error'] = 'Vous n\'avez pas la permission';
      Router::redirect('dashboard');
    }
  }


  public function getAnimalsOfHabitat($request)
  {
    if ($_SERVER['REQUEST_METHOD'] === "GET") {

      try {
        $id = htmlspecialchars($request['id']);
        Validator::strIsInt($id);

        $animalRepo = new Animal();
        $animals = $animalRepo->fetchAnimalsByHabitat($id);

        echo  json_encode($animals);
      } catch (Exception $e) {
        $_SESSION['error'] = $e->getMessage();
        http_response_code(500);
        header('content-type:application/json');
        echo json_encode(['error' => $e->getMessage()]);
      }
    } else {
      $_SESSION['error'] = 'Vous n\'avez pas la permission';
      http_response_code(401);
      header('content-type:application/json');
      echo json_encode(['error' => 'Vous n\'avez pas la permission']);
    }
  }
}
