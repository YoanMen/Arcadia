<?php

namespace App\Controller;

use App\Core\Exception\DatabaseException;
use App\Core\Exception\ValidatorException;
use App\Core\Router;
use App\Core\Security;
use App\Core\UploadFile;
use App\Core\Validator;
use App\Model\Animal;
use App\Model\Habitat;
use App\Model\Image;
use App\Model\ReportAnimal;
use Exception;

class HabitatController extends Controller
{
  public function index()
  {

    $currentPage = 0;
    if (isset($_GET['page'])) {
      $getPage = filter_var($_GET['page'], FILTER_VALIDATE_INT);
      if (is_int($getPage)) {
        $currentPage = $getPage - 1;
      }
    }

    // initialize data for habitat page
    $habitatRepository = new Habitat;
    $nbHabitats = $habitatRepository->count();
    $habitatRepository->setLimit(10);
    $totalPages = ceil($nbHabitats / $habitatRepository->getLimit());
    $first = $currentPage * $habitatRepository->getLimit();
    $habitatRepository->setOffset($first);
    $habitats = $habitatRepository->fetchAll();

    // search image for all habitat
    if ($habitats) {
      foreach ($habitats as $habitat) {
        $habitat->findImages();
      }
    }

    $this->show('habitat', [
      'habitats' => $habitats,
      'totalPages' => $totalPages,
      'currentPage' => $currentPage + 1
    ]);
  }

  // show animal page
  public function showAnimal($request)
  {
    try {
      $name = htmlspecialchars($request['animalName']);
      $name = str_replace('-', ' ', $name);
      $habitatName = htmlspecialchars($request['name']);
      $habitatName = str_replace('-', ' ', $habitatName);

      $habitatRepository = new Habitat();
      $animalRepository = new Animal();


      // find habitat of animal
      $habitat = $habitatRepository->findOneBy(['name' => $habitatName]);

      // find animal with data
      if (isset($habitat)) {
        $animal = $animalRepository->findOneBy(['name' => $name, 'habitatId' => $habitat->getId()]);

        if ($animal) {
          $animal->findImages();
          $reportRepository = new ReportAnimal();
          $report =  $reportRepository->findOneBy(['animalId' => $animal->getId()]);

          $this->show('animal', [
            'animal' => $animal,
            'habitat' => $habitatName,
            'report' => $report
          ]);
        } else {
          throw new DatabaseException('Animal not exist');
        }
      } else {
        throw new DatabaseException('Habitat for animal not exist');
      }
    } catch (Exception $e) {
      Router::redirect('error');
    }
  }

  // fetch get image corresponding habitat
  public function getHabitatImages()
  {
    $csrf = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';

    if (Security::verifyCsrf($csrf) && $_SERVER['REQUEST_METHOD'] === "POST" && Security::isAdmin()) {
      try {
        $content = trim(file_get_contents('php://input'));
        $data = json_decode($content, true);


        // get images for habitat
        $id = htmlspecialchars($data['params']['id']);
        $habitatRepo = new Habitat();
        $habitatImages = $habitatRepo->fetchImages($id);

        echo json_encode(['data' => $habitatImages]);
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

  // show habitat page
  public function showHabitat($request)
  {
    try {
      $name = htmlspecialchars($request['name']);
      $name = str_replace('-', ' ', $name);

      $habitatRepository = new Habitat();
      $animalRepository = new Animal();

      $habitat = $habitatRepository->findOneBy(['name' => $name]);

      if (!$habitat) {
        throw new DatabaseException('Habitat not exist');
      }

      // find image for habitat
      $habitat->findImages();

      // find all animals and images corresponding habitat
      $animals = $animalRepository->find(['habitatId' => $habitat->getId()]);

      if ($animals) {
        foreach ($animals as $animal) {
          $animal->findImages();
        }
      }

      $this->show('habitatDetails', [
        'habitat' => $habitat,
        'animals' => $animals
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
          // search image corresponding by id
          $id = htmlspecialchars($data['id']);
          $imageRepo = new Image();
          $image = $imageRepo->findOneBy(['id' => $id]);

          if ($image) {
            // remove image in folder and ddb
            UploadFile::remove($image->getPath());
            $imageRepo->delete(['id' => $id]);

            $_SESSION['success'] = "image supprimé";
            echo json_encode(['success' => 'image supprimé']);
          } else {
            http_response_code(201);
            $_SESSION['error'] = "impossible de récupéré l\'image ";
            throw new DatabaseException('impossible de récupéré l\'image ');
          }
        } catch (Exception $e) {
          http_response_code(500);
          $_SESSION['error'] = $e->getMessage();
          echo json_encode(['error' => $e->getMessage()]);
        }
      } else {
        http_response_code(401);
        echo json_encode(['error' => 'Clé CSRF non valide']);
      }
    } else {
      http_response_code(401);
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

          $path =   UploadFile::upload();
          $imageRepo = new Image();
          $habitatRepo = new Habitat();

          $imageRepo->insert(['path' => $path]);
          $image = $imageRepo->findOneBy(['path' => $path]);

          $habitatRepo->insertImage($id, $image->getId());

          $_SESSION['success'] = 'Image ajouté';
          echo json_encode(['path' => $image->getPath(), 'id' =>  $image->getId()]);
        } catch (Exception $e) {
          http_response_code(500);
          $_SESSION['error'] = $e->getMessage();
          echo json_encode(['error' => $e->getMessage()]);
        }
      } else {
        http_response_code(401);
        $_SESSION['error'] = 'Clé CSRF non valide';
        echo json_encode(['error' => 'Clé CSRF non valide']);
      }
    } else {
      http_response_code(401);
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

          $habitatRepo = new Habitat();
          $nbUsers = $habitatRepo->habitatsCount($search);

          $habitatRepo->setLimit(10);
          $totalPage = ceil($nbUsers / $habitatRepo->getLimit());
          $first = ($currentPage - 1) * $habitatRepo->getLimit();

          $habitatRepo->setOffset($first);
          $data = $habitatRepo->fetchHabitats($search, $orderBy, $order);

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

              $habitatRepo = new Habitat();

              $habitat = $habitatRepo->findOneBy(['name' => $name]);
              if ($habitat && $habitat->getId() != $id) {
                throw new ValidatorException('un habitat avec ce nom existe déjà');
              }

              $habitatRepo->update(['name' => $name, 'description' => $description], $id);
              Router::redirect('dashboard/habitats');
            } catch (Exception $e) {
              $_SESSION['error'] =  $e->getMessage();
            }
          } else {

            $_SESSION['error'] = 'Clé CSRF non valide';
          }
        }

        $habitatRepo = new Habitat();
        $habitat = $habitatRepo->findOneBy(['id' => $request['id']]);
        $images = $habitatRepo->fetchImages($request['id']);
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


  // get Habitats for fetch depending params
  public function getHabitats()
  {
    $csrf = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
    if (Security::verifyCsrf($csrf) && $_SERVER['REQUEST_METHOD'] === 'POST' && Security::isAdmin()) {
      try {

        $content = trim(file_get_contents('php://input'));
        $data = json_decode($content, true);

        // get all params
        $search = htmlspecialchars($data['params']['search']);
        $order = htmlspecialchars($data['params']['order']);
        $orderBy = htmlspecialchars($data['params']['orderBy']);
        $count = htmlspecialchars($data['params']['count']);

        $habitatsRepo = new Habitat();

        $habitatCount = $habitatsRepo->habitatsCount($search);
        $remainCount = $habitatCount - $count;


        // check if remaining data
        if ($remainCount > 0) {
          $habitatsRepo->setOffset($count);
          $habitatsComment = $habitatsRepo->fetchHabitats($search, $order, $orderBy);

          echo json_encode(['data' => $habitatsComment, 'totalCount' => $habitatCount]);
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

  public function createHabitat()
  {
    $csrf = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';

    if (Security::verifyCsrf($csrf) && $_SERVER['REQUEST_METHOD'] === "POST" && Security::isAdmin()) {
      try {
        $name = $_POST['name'];
        $description = $_POST['description'];

        $name = htmlspecialchars($name);
        $description = htmlspecialchars($description);

        $name = trim($name);
        $description = ltrim($description, ' ');

        $name = ucfirst($name);
        $description = ucfirst($description);

        Validator::strLengthCorrect($name, 3, 60);
        Validator::strWithoutSpecialCharacters($name);
        Validator::strMinLengthCorrect($description, 10);

        $habitatRepo = new Habitat();

        $habitat = $habitatRepo->findOneBy(['name' => $name]);

        if ($habitat) {
          throw new ValidatorException('un habitat avec ce nom existe déjà');
        }

        // upload file and return path
        $path =  UploadFile::upload();
        $imageRepo = new Image();

        // insert on image table new image
        $imageRepo->insert(['path' => $path]);
        // get id of image
        $image = $imageRepo->findOneBy(['path' => $path]);

        // insert new habitat on table
        $habitatRepo->insert(['name' => $name, 'description' => $description]);
        $habitat = $habitatRepo->findOneBy(['name' => $name]);

        // send habitat_id and image_id to habitat_image
        $habitatRepo->insertImage($habitat->getId(), $image->getId());

        echo json_encode(['success' => 'l/`habitat à été crée']);
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

  public function updateHabitat()
  {
    $csrf = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';

    if (Security::verifyCsrf($csrf) && $_SERVER['REQUEST_METHOD'] === "POST" && Security::isAdmin()) {
      try {
        $content = trim(file_get_contents('php://input'));
        $data = json_decode($content, true);

        $id = htmlspecialchars($data['params']['id']);
        $name = htmlspecialchars($data['params']['name']);
        $description = htmlspecialchars($data['params']['description']);

        $name = trim($name);
        $description = trim($description);

        $name = ucfirst($name);

        Validator::strIsInt($id);
        Validator::strLengthCorrect($name, 3, 60);
        Validator::strWithoutSpecialCharacters($name);
        Validator::strMinLengthCorrect($description, 10);

        $habitatRepo = new Habitat();

        $habitat = $habitatRepo->findOneBy(['name' => $name]);
        if ($habitat && $habitat->getId() != $id) {
          throw new ValidatorException('un habitat avec ce nom existe déjà');
        }

        $habitatRepo->update(['name' => $name, 'description' => $description], $id);

        echo json_encode(['success' => 'l/`habitat à été modifié']);
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

  public function deleteHabitat()
  {
    $csrf = $_SERVER['HTTP_X_CSRF_TOKEN'];

    if (Security::verifyCsrf($csrf) && Security::isAdmin() && $_SERVER['REQUEST_METHOD'] === 'DELETE') {

      try {
        $content = trim(file_get_contents('php://input'));
        $data = json_decode($content, true);

        $id = htmlspecialchars($data['params']['id']);

        $habitatRepo = new Habitat();
        $habitatImages = $habitatRepo->fetchImages($id);


        // delete image of habitat
        if ($habitatImages) {
          foreach ($habitatImages as $image) {
            UploadFile::remove($image['path']);
          }
        }

        // delete habitat
        $habitatRepo->delete(['id' => $id]);

        echo json_encode(['success' => 'l/`habitat à été supprimé']);
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
