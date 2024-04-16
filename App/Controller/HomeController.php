<?php

namespace App\Controller;

use App\Core\Exception\DatabaseException;
use App\Model\Habitat;
use App\Model\Schedule;
use App\Model\Service;

class HomeController extends Controller
{
	public function index()
	{

		$habitatRepository = new Habitat;
		$habitatRepository->setLimit(5);

		$habitats = $habitatRepository->fetchAll();

		if ($habitats) {
			// add image to habitats
			for ($i = 0; $i < count($habitats); $i++) {
				$habitats[$i]->findImages();
			}
		}

		$schedulesRepository = new Schedule();
		$schedules = $schedulesRepository->fetchAll();

		$this->show('home', [
			'habitats' => $habitats,
			'schedules' => $schedules,
		]);
	}

	public function initMenu()
	{

		if ($_SERVER['REQUEST_METHOD'] === 'GET') {
			try {
				$servicesRepository = new Service;
				$services = $servicesRepository->fetchMenuService();
				$habitatRepository = new Habitat;
				$habitats = $habitatRepository->fetchMenuHabitat();

				header('Content-Type: application/json');
				echo json_encode(['services' => $services, 'habitats' => $habitats]);
			} catch (DatabaseException $e) {
				http_response_code(500);
				echo json_encode(['error' => $e]);
			}
		}
	}
}
