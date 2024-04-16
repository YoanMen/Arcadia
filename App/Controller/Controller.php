<?php
namespace App\Controller;

class Controller
{
	/**
	 * Function to Show View
	 * @param $name of Route ex('home')
	 * @param $data passing data to View
	 */
	public function show($name, $data = [])
	{
		$filename = "../App/View/" . $name . ".php";
		if (file_exists($filename)) {
			$data;
			require_once $filename;

		} else {

			$filename = "../App/View/404.php";
			require_once $filename;
		}
	}
}