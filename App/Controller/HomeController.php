<?php

namespace App\Controller;

use App\Core\Exception\DatabaseException;
use App\Model\Advice;
use App\Model\Habitat;
use App\Model\Schedule;
use App\Model\Service;

class HomeController extends Controller
{
	public function index()
	{

		$servicesRepository = new Service;
		$services = $servicesRepository->fetchAll();
		$habitatRepository = new Habitat;
		$habitats = $habitatRepository->fetchAll();

		$adviceRepository = new Advice();
		$adviceRepository->setOrderBy('asc');
		$adviceRepository->setLimit(1);
		$advice = $adviceRepository->find(['approved' => true]);

		// add image to some Habitats
		for ($i = 0; $i < 2; $i++) {
			$habitats[$i]->findImages();
		}
		$schedulesRepository = new Schedule();
		$schedules = $schedulesRepository->fetchAll();

		$this->show('home', [
			'services' => $services,
			'habitats' => $habitats,
			'schedules' => $schedules,
			'advice' => $advice
		]);
	}


	public function initMenu()
	{
		try {
			$servicesRepository = new Service;
			$services = $servicesRepository->fetchAll(associative: true);
			$habitatRepository = new Habitat;
			$habitats = $habitatRepository->fetchAll(associative : true);
			header('Content-Type: application/json');
			echo json_encode(['services' => $services, 'habitats' => $habitats]);
		} catch (DatabaseException $e) {
			http_response_code(500);
			echo json_encode(['error' => $e]);
		}
	}
}
