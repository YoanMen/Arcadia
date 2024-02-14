<?php
namespace App\Controller;

use App\Model\Habitat;

class HomeController extends Controller
{
	public function index()
	{
		$this->show('home');

		$habitas = new Habitat;
			$habitas->find(['id' => 1]);
	}


}
