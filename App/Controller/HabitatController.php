<?php

namespace App\Controller;

use App\Core\Exception\DatabaseException;
use App\Core\Router;
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
}
