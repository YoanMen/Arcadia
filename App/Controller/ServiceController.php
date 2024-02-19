<?php

namespace App\Controller;

use App\Core\Router;
use App\Model\Habitat;
use App\Model\Service;

class ServiceController extends Controller
{
  public function index()
  {
    $habitatRepository = new Habitat();
    $habitats = $habitatRepository->fetchAll();
    $serviceRepository = new Service();
    $services = $serviceRepository->fetchAll();

    $this->show('service', [
      'habitats' => $habitats,
      'services' => $services
    ]);
  }


  public function showService($request)
  {
    try {
      $name =  htmlspecialchars($request['name']);
      $name = str_replace('-', ' ', $name);

      $serviceRepository = new Service();
      $habitatRepository = new Habitat();
      $habitats = $habitatRepository->fetchAll();
      $services = $serviceRepository->fetchAll();
      $service = $serviceRepository->findOneBy(['name' => $name]);

      if (isset($service)) {
        $this->show('serviceDetails', [

          'services' => $services,
          'habitats' => $habitats,
          'service' => $service
        ]);
      } else {
        throw new \Exception('Service not exist');
      }
    } catch (\Exception $e) {
      Router::redirect('/error');
    }
  }
}

$habitatRepository = new Habitat;
$habitats = $habitatRepository->fetchAll();
