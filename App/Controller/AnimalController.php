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

class AnimalController extends Controller
{
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
            $_SESSION['error'] = "impossible de récupéré l'image ";
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

  public function uploadImage()
  {
    if (Security::isAdmin() && $_SERVER['REQUEST_METHOD'] === "POST") {


      $csrf = htmlspecialchars($_POST['csrf']);
      if (Security::verifyCsrf($csrf)) {

        try {
          $id = htmlspecialchars($_POST['id']);


          Validator::strIsInt($id);

          $path =  UploadFile::upload();
          $imageRepo = new Image();
          $animalRepo = new Animal();

          $imageRepo->insert(['path' => $path]);
          $image = $imageRepo->findOneBy(['path' => $path]);

          $animalRepo->insertImage($id, $image->getId());

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

          $animalRepo = new Animal();
          $nbUsers = $animalRepo->animalsCount($search);

          $animalRepo->setLimit(10);
          $totalPage = ceil($nbUsers / $animalRepo->getLimit());
          $first = ($currentPage - 1) * $animalRepo->getLimit();

          $animalRepo->setOffset($first);
          $data = $animalRepo->fetchAnimals($search, $order, $orderBy);

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

              $animalRepo = new Animal();

              $animal = $animalRepo->findOneBy(['name' => $name]);

              if ($animal) {
                throw new ValidatorException('un animal avec ce nom existe déjà');
              }

              // upload file and return path
              $path =  UploadFile::upload();
              $imageRepo = new Image();

              // insert on image table new image
              $imageRepo->insert(['path' => $path]);
              // get id of image
              $image = $imageRepo->findOneBy(['path' => $path]);

              // insert new animal on table
              $animalRepo->insert(['name' => $name, 'race' => $race, 'habitatId' => $habitat]);
              $animal = $animalRepo->findOneBy(['name' => $name]);

              // send animal_id and image_id to animal_image
              $animalRepo->insertImage($animal->getId(), $image->getId());

              $_SESSION['success'] = 'L\'animal ' . $name . ' à été crée';
              Router::redirect('dashboard/animaux');
            } catch (Exception $e) {
              $_SESSION['error'] =  $e->getMessage();
            }
          } else {

            $_SESSION['error'] = 'Clé CSRF non valide';
          }
        }

        $habitaRepo = new Habitat();
        $habitats = $habitaRepo->fetchAllHabitatsWithoutComment();

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

              $animalRepo = new Animal();

              $animal = $animalRepo->findOneBy(['name' => $name]);

              if ($animal && $animal->getId() != $id) {
                throw new ValidatorException('un animal avec ce nom existe déjà');
              }

              $animalRepo->update(['name' => $name, 'race' => $race, 'habitatId' => $habitat], $id);

              $_SESSION['success'] =  'L\'animal ' . $name . ' à été modifié';

              Router::redirect('dashboard/animaux');
            } catch (Exception $e) {
              $_SESSION['error'] =  $e->getMessage();
            }
          } else {

            $_SESSION['error'] = 'Clé CSRF non valide';
          }
        }

        $animalRepo = new Animal();
        $animal = $animalRepo->findOneBy(['id' => $request['id']]);
        $images = $animalRepo->fetchImages($request['id']);

        $habitaRepo = new Habitat();
        $habitats = $habitaRepo->fetchAllHabitatsWithoutComment();

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

          $animalRepo = new Animal();
          $animalImages = $animalRepo->fetchImages($id);

          // delete image of animal
          if ($animalImages) {
            foreach ($animalImages as $image) {
              UploadFile::remove($image['path']);
            }
          }

          // delete animal
          $animalRepo->delete(['id' => $id]);

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
}
