<?php

namespace App\Controller;

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
		$advice = $adviceRepository->fetchAll();

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
}
