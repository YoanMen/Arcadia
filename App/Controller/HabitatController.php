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

  // Page showing all habitats
  public function index()
  {

    $page = $_GET['page'] ?? 1;
    $currentPage = $page;


    // initialize data for habitat page
    $habitatRepository = new Habitat;
    $nbHabitats = $habitatRepository->count();
    $habitatRepository->setLimit(10);
    $totalPages = ceil($nbHabitats / $habitatRepository->getLimit());
    $first = ($currentPage - 1) * $habitatRepository->getLimit();
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
      'totalPages' => ($totalPages != 0) ?  $totalPages : 1,
      'currentPage' =>  $currentPage,
    ]);
  }

  // show habitat
  public function showHabitat($request)
  {
    try {

      $page = $_GET['page'] ?? 1;
      $currentPage = $page;

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

          $path =   UploadFile::upload();
          $imageRepo = new Image();
          $habitatRepo = new Habitat();

          $imageRepo->insert(['path' => $path]);
          $image = $imageRepo->findOneBy(['path' => $path]);

          $habitatRepo->insertImage($id, $image->getId());

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

          $habitatRepo = new Habitat();
          $nbUsers = $habitatRepo->habitatsCount($search);

          $habitatRepo->setLimit(10);
          $totalPage = ceil($nbUsers / $habitatRepo->getLimit());
          $first = ($currentPage - 1) * $habitatRepo->getLimit();

          $habitatRepo->setOffset($first);
          $data = $habitatRepo->fetchHabitats($search, $order, $orderBy);

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

              $habitatRepo = new Habitat();

              $habitat = $habitatRepo->findOneBy(['name' => $name]);

              if ($habitat) {
                throw new ValidatorException('un habitat avec ce nom existe déjà');
              }

              // upload file and return path
              $path =  UploadFile::upload();
              $imageRepo = new Image();

              // insert on image table image
              $imageRepo->insert(['path' => $path]);
              // get id of image
              $image = $imageRepo->findOneBy(['path' => $path]);

              // insert new habitat on table
              $habitatRepo->insert(['name' => $name, 'description' => $description]);
              $habitat = $habitatRepo->findOneBy(['name' => $name]);

              // send habitat_id and image_id to habitat_image
              $habitatRepo->insertImage($habitat->getId(), $image->getId());

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

              $habitatRepo = new Habitat();

              $habitat = $habitatRepo->findOneBy(['name' => $name]);
              if ($habitat && $habitat->getId() != $id) {
                throw new ValidatorException('un habitat avec ce nom existe déjà');
              }

              $habitatRepo->update(['name' => $name, 'description' => $description], $id);
              $_SESSION['success'] =  'L\'habitat ' . $name . ' à été modifié';

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
  public function delete($request)
  {
    if (Security::isAdmin() && $_SERVER['REQUEST_METHOD'] === "POST") {
      $csrf = $_POST['csrf_token'] ?? '';
      if (Security::verifyCsrf($csrf)) {
        try {
          $id =  htmlspecialchars($request['id']);
          Validator::strIsInt($id);

          $habitatRepo = new Habitat();

          $habitatImages = $habitatRepo->fetchImages($id);

          if ($habitatImages) {
            foreach ($habitatImages as $image) {
              UploadFile::remove($image['path']);
            }
          }

          // delete habitat
          $habitatRepo->delete(['id' => $id]);
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
