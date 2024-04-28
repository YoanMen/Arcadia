<?php

namespace App\Controller;

use App\Model\CouchDB;
use App\Core\Exception\DatabaseException;
use App\Core\Router;
use App\Core\Security;
use App\Core\UploadFile;
use App\Core\Validator;
use App\Model\Admin;
use App\Model\Animal;
use App\Model\CouchDB as ModelCouchDB;
use App\Model\Habitat;
use App\Model\Image;
use App\Model\ReportAnimal;
use Exception;

class AnimalController extends Controller
{
  private Animal $animal;
  private Habitat $habitat;
  public function __construct()
  {
    $this->animal = new Animal();
    $this->habitat = new Habitat();
  }

  // animal detail page
  public function showAnimal($request)
  {
    try {
      $name = htmlspecialchars($request['animalName']);
      $name = str_replace('-', ' ', $name);
      $habitatName = htmlspecialchars($request['name']);
      $habitatName = str_replace('-', ' ', $habitatName);

      // find habitat of animal
      $habitat = $this->habitat->findOneBy(['name' => $habitatName]);

      // find animal with data
      if (isset($habitat)) {
        $animal = $this->animal->findOneBy(['name' => $name, 'habitatId' => $habitat->getId()]);

        if ($animal) {
          // get images of this animal
          $animal->findImagesForThisAnimal();

          // get report detail by veterinary
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

          $image = new Image();
          $image->deleteImage($id);
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

  public function uploadImage()
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

          $this->animal->insertImage($id, $image->getId());

          $_SESSION['success'] = 'Image ajouté';
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

          $nbUsers = $this->animal->animalsCount($search);

          $this->animal->setLimit(10);
          $totalPage = ceil($nbUsers / $this->animal->getLimit());
          $first = ($currentPage - 1) * $this->animal->getLimit();

          $this->animal->setOffset($first);
          $data = $this->animal->fetchAnimals($search, $order, $orderBy);

          $this->show('admin/animal/table', [
            'params' => ['search' => $search, 'order' => $order],
            'animals' => $data,
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
              $race = htmlspecialchars($_POST['race']);
              $habitat = htmlspecialchars($_POST['habitat']);

              $name = trim($name);
              $race = trim($race);

              $name = strtolower($name);
              $name = ucfirst($name);

              $race = strtolower($race);
              $race = ucfirst($race);

              Validator::strLengthCorrect($name, 3, 40);
              Validator::strWithoutSpecialCharacters($name, 'Le nom ne doit pas contenir de caractère spéciales');
              Validator::strLengthCorrect($race, 3, 40);
              Validator::strIsInt($habitat);

              $admin = new Admin();
              $admin->createAnimal($name, $race, $habitat);

              $_SESSION['success'] = 'L\'animal ' . $name . ' à été crée';
              Router::redirect('dashboard/animaux');
            } catch (Exception $e) {
              $_SESSION['error'] =  $e->getMessage();
            }
          } else {

            $_SESSION['error'] = 'Clé CSRF non valide';
          }
        }

        $habitats = $this->habitat->fetchAllHabitatsWithoutComment();

        $this->show('admin/animal/add', [
          'name' => $name ?? '',
          'race' => $race ?? '',
          'habitat' => $habitat ?? '',
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
              $race = htmlspecialchars($_POST['race']);
              $habitat =  htmlspecialchars($_POST['habitat']);

              $name = trim($name);
              $race = trim($race);

              $name = ucfirst($name);
              $race = ucfirst($race);

              Validator::strIsInt($id);
              Validator::strIsInt($habitat);
              Validator::strLengthCorrect($name, 3, 40);
              Validator::strWithoutSpecialCharacters($name, 'Le nom ne doit pas contenir de caractère spéciales');
              Validator::strLengthCorrect($race, 3, 40);

              $admin = new Admin();
              $admin->updateAnimal($name, $race, $habitat, $id);

              $_SESSION['success'] =  'L\'animal ' . $name . ' à été modifié';
              Router::redirect('dashboard/animaux');
            } catch (Exception $e) {
              $_SESSION['error'] =  $e->getMessage();
            }
          } else {

            $_SESSION['error'] = 'Clé CSRF non valide';
          }
        }

        $animal = $this->animal->findOneBy(['id' => $request['id']]);
        $images = $this->animal->fetchImages($request['id']);

        $habitats = $this->habitat->fetchAllHabitatsWithoutComment();

        $this->show('admin/animal/edit', [
          'animal' => $animal,
          'images' => $images,
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

  public function delete($request)
  {
    if (Security::isAdmin() && $_SERVER['REQUEST_METHOD'] === "POST") {
      $csrf = $_POST['csrf_token'] ?? '';
      if (Security::verifyCsrf($csrf)) {
        try {
          $id =  htmlspecialchars($request['id']);
          Validator::strIsInt($id);

          $admin = new Admin();
          $admin->deleteAnimal($id);

          $_SESSION['success'] = 'l\'animal à été supprimé';
        } catch (Exception $e) {
          $_SESSION['error'] =  $e->getMessage();
        }
      } else {
        $_SESSION['error'] = 'Clé CSRF pas valide';
      }
      Router::redirect('dashboard/animaux');
    } else {
      $_SESSION['error'] = 'Vous n\'avez pas la permission';
      Router::redirect('dashboard');
    }
  }

  public function addClick()
  {

    if ($_SERVER['REQUEST_METHOD'] === "POST") {

      $content = trim(file_get_contents("php://input"));
      $data = json_decode($content, true);
      $csrf = $data['csrf_token'];
      $id =  htmlspecialchars($data['id']);

      if (Security::verifyCsrf($csrf)) {
        try {
          Validator::strIsInt($id);
          $animal = $this->animal->findOneBy(['id' => $id]);

          if ($animal) {
            // add click for animal
            $couchDb  = new CouchDB();

            $couchDb->addClick($id);
            http_response_code(201);
            return;
          }

          http_response_code(404);
        } catch (Exception $e) {
          http_response_code(500);
        }
      } else {
        http_response_code(401);
      }
    } else {
      http_response_code(405);
    }
  }
}
