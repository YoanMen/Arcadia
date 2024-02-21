<?php

namespace App\Controller;

use App\Core\Exception\DatabaseException;
use App\Core\Router;
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
}
