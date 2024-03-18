<?php

namespace App\Controller;

use App\Core\Exception\DatabaseException;
use App\Core\Router;
use App\Core\Security;
use App\Model\Animal;
use App\Model\Habitat;
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

    $habitatRepository = new Habitat;
    $nbHabitats = $habitatRepository->count();
    $habitatRepository->setLimit(10);
    $totalPages = ceil($nbHabitats / $habitatRepository->getLimit());
    $first = $currentPage * $habitatRepository->getLimit();
    $habitatRepository->setOffset($first);
    $habitats = $habitatRepository->fetchAll();

    foreach ($habitats as $habitat) {
      $habitat->findImages();
    }

    $this->show('habitat', [
      'habitats' => $habitats,
      'totalPages' => $totalPages,
      'currentPage' => $currentPage + 1
    ]);
  }

  public function getHabitats()
  {
    $csrf = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
    if (Security::verifyCsrf($csrf) && $_SERVER['REQUEST_METHOD'] === 'POST' && Security::isAdmin()) {
      try {

        $content = trim(file_get_contents('php://input'));
        $data = json_decode($content, true);

        $search = htmlspecialchars($data['search']);
        $order = htmlspecialchars($data['order']);
        $orderBy = htmlspecialchars($data['orderBy']);
        $count = htmlspecialchars($data['count']);

        $habitatsRepo = new Habitat();

        $habitatCount = $habitatsRepo->habitatsCount($search);
        $remainCount = $habitatCount - $count;

        if ($remainCount > 0) {
          $habitatsRepo->setOffset($count);
          $habitatsComment = $habitatsRepo->fetchHabitats($search, $order, $orderBy);

          echo json_encode(['data' => $habitatsComment, 'totalCount' => $habitatCount]);
        } else {
          echo json_encode(['error' => 'aucun résultat']);
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

  public function getHabitatImages()
  {
    $csrf = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';

    if (Security::verifyCsrf($csrf) && $_SERVER['REQUEST_METHOD'] === "POST" && Security::isAdmin()) {
      try {
        $content = trim(file_get_contents('php://input'));
        $data = json_decode($content, true);

        $id = htmlspecialchars($data['id']);

        $habitatRepo = new Habitat();

        $habitatImages = $habitatRepo->fetchImages($id);

        echo json_encode(['data' => $habitatImages]);
      } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
      }
    } else {
      http_response_code(401);
      echo json_encode(['error' => 'CSRF token is not valid']);
    }
  }


  public function showHabitat($request)
  {
    try {
      $name = htmlspecialchars($request['name']);
      $name = str_replace('-', ' ', $name);

      $habitatRepository = new Habitat();
      $animalRepository = new Animal();

      $habitat = $habitatRepository->findOneBy(['name' => $name]);
      $habitat->findImages();

      $animals = $animalRepository->find(['habitatId' => $habitat->getId()]);
      foreach ($animals as $animal) {
        $animal->findImages();
      }

      if (isset($habitat)) {
        $this->show('habitatDetails', [
          'habitat' => $habitat,
          'animals' => $animals
        ]);
      } else {
        throw new DatabaseException('Habitat not exist');
      }
    } catch (Exception $e) {
      Router::redirect('error');
    }
  }

  public function showAnimal($request)
  {
    try {
      $name = htmlspecialchars($request['animalName']);
      $name = str_replace('-', ' ', $name);
      $habitatName = htmlspecialchars($request['name']);
      $habitatName = str_replace('-', ' ', $habitatName);

      $habitatRepository = new Habitat();
      $animalRepository = new Animal();

      $habitat = $habitatRepository->findOneBy(['name' => $habitatName]);

      if (isset($habitat)) {
        $animal = $animalRepository->findOneBy(['name' => $name, 'habitatId' => $habitat->getId()]);

        if (isset($animal)) {
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

  public function updateHabitat()
  {
    $csrf = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';

    if (Security::verifyCsrf($csrf) && $_SERVER['REQUEST_METHOD'] === "PUT" && Security::isAdmin()) {
      try {
        $content = trim(file_get_contents('php://input'));
        $data = json_decode($content, true);

        $id = htmlspecialchars($data['id']);
        $name = htmlspecialchars($data['name']);
        $description = htmlspecialchars($data['description']);


        $habitatRepo = new Habitat();


        // check if have image, if dont have image error

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
}
