<?php

namespace App\Controller;

use App\Core\Exception\DatabaseException;
use App\Core\Exception\ValidatorException;
use App\Core\Security;
use App\Core\UploadFile;
use App\Core\Validator;
use App\Model\Animal;
use App\Model\Habitat;
use App\Model\Image;
use Exception;

class AnimalController extends Controller
{
  public function getAnimalImages()
  {
    $csrf = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';

    if (Security::verifyCsrf($csrf) && $_SERVER['REQUEST_METHOD'] === "POST" && Security::isAdmin()) {
      try {
        $content = trim(file_get_contents('php://input'));
        $data = json_decode($content, true);

        // get images for habitat
        $id = htmlspecialchars($data['params']['id']);

        Validator::strIsInt($id);

        $animalRepo = new Animal();
        $animalImages = $animalRepo->fetchImages($id);

        echo json_encode(['data' => $animalImages]);
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

  public function  deleteImage()
  {
    $csrf = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';


    if (Security::verifyCsrf($csrf) && $_SERVER['REQUEST_METHOD'] === 'DELETE' && Security::isAdmin()) {
      try {
        $content = trim(file_get_contents('php://input'));
        $data = json_decode($content, true);


        // search image corresponding by id
        $id = htmlspecialchars($data['params']['id']);
        $imageRepo = new Image();
        $image = $imageRepo->findOneBy(['id' => $id]);

        if ($image) {

          // remove image in folder and ddb
          UploadFile::remove($image->getPath());
          $imageRepo->delete(['id' => $id]);

          echo json_encode(['success' => 'image supprimé']);
        } else {
          throw new DatabaseException('impossible de récupéré l\'image ');
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

  public function uploadImage()
  {
    $csrf = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
    if (Security::verifyCsrf($csrf) && $_SERVER['REQUEST_METHOD'] === "POST" && Security::isAdmin()) {
      try {
        $id = $_POST['id'] ?? '';
        $id = htmlspecialchars($id);

        Validator::strIsInt($id);

        $path =  UploadFile::upload();
        $imageRepo = new Image();
        $animalRepo = new Animal();

        $imageRepo->insert(['path' => $path]);
        $image = $imageRepo->findOneBy(['path' => $path]);

        $animalRepo->insertImage($id, $image->getId());

        echo json_encode(['path' => $image->getPath(), 'id' =>  $image->getId()]);
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
  public function getAnimals()
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

        $animalRepo = new Animal();
        $habitatRepo = new Habitat();

        $habitat = $habitatRepo->fetchAll(true);

        $animalCount = $animalRepo->animalsCount($search);
        $remainCount = $animalCount - $count;

        // check if remaining data
        if ($remainCount > 0) {
          $animalRepo->setOffset($count);
          $animals = $animalRepo->fetchAnimals($search, $order, $orderBy);

          echo json_encode(['data' => $animals, 'totalCount' => $animalCount, 'habitat' => $habitat]);
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

  public function getAnimalsByHabitat()
  {
    $csrf = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
    if (Security::verifyCsrf($csrf) && $_SERVER['REQUEST_METHOD'] === 'POST' && Security::isEmployee()) {
      try {

        $content = trim(file_get_contents('php://input'));
        $data = json_decode($content, true);


        $id = htmlspecialchars($data['params']['id']);

        if (isset($id) && is_int(intval($id))) {
          $animalRepo = new Animal();
          $animals = $animalRepo->fetchAnimalsByHabitat($id);

          echo json_encode(['data' => $animals]);
        } else {
          throw new ValidatorException('id n\'est pas valide');
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

  public function createAnimal()
  {
    $csrf = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? "";

    if (Security::verifyCsrf($csrf) && $_SERVER['REQUEST_METHOD'] === "POST" && Security::isAdmin()) {
      try {
        $name = $_POST['name'];
        $race = $_POST['race'];
        $habitat = $_POST['habitat'];

        $name = htmlspecialchars($name);
        $race = htmlspecialchars($race);
        $habitat = htmlspecialchars($habitat);

        $name = trim($name);
        $race = trim($race);

        $name = strtolower($name);
        $name = ucfirst($name);

        $race = strtolower($race);
        $race = ucfirst($race);

        $animalRepo = new Animal();

        Validator::strLengthCorrect($name, 3, 40);
        Validator::strWithoutSpecialCharacters($name);
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

  public function updateAnimal()
  {
    $csrf = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';

    if (Security::verifyCsrf($csrf) && $_SERVER['REQUEST_METHOD'] === "POST" && Security::isAdmin()) {
      try {
        $content = trim(file_get_contents('php://input'));
        $data = json_decode($content, true);

        $id = htmlspecialchars($data['params']['id']);
        $name = htmlspecialchars($data['params']['name']);
        $race = htmlspecialchars($data['params']['race']);
        $habitat =  htmlspecialchars($data['params']['habitat']);

        $name = trim($name);
        $race = trim($race);

        $name = ucfirst($name);
        $race = ucfirst($race);

        Validator::strIsInt($id);
        Validator::strIsInt($habitat);
        Validator::strLengthCorrect($name, 3, 40);
        Validator::strWithoutSpecialCharacters($name);
        Validator::strLengthCorrect($race, 3, 40);

        $animalRepo = new Animal();

        $animal = $animalRepo->findOneBy(['name' => $name]);

        if ($animal && $animal->getId() != $id) {
          throw new ValidatorException('un animal avec ce nom existe déjà');
        }

        $animalRepo->update(['name' => $name, 'race' => $race, 'habitatId' => $habitat], $id);

        echo json_encode(['success' => 'l/`animal à été modifié']);
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

  public function deleteAnimal()
  {
    $csrf = $_SERVER['HTTP_X_CSRF_TOKEN'];

    if (Security::verifyCsrf($csrf) && Security::isAdmin() && $_SERVER['REQUEST_METHOD'] === 'DELETE') {

      try {
        $content = trim(file_get_contents('php://input'));
        $data = json_decode($content, true);

        $id = htmlspecialchars($data['params']['id']);

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

        echo json_encode(['success' => 'l/`animal à été supprimé']);
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
