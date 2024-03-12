<?php

namespace App\Controller;

use App\Core\Security;
use App\Model\ReportAnimal;

class ReportAnimalController extends Controller
{

  public function getReportAnimal()
  {
    $csrf = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';

    if (Security::verifyCsrf($csrf) && $_SERVER['REQUEST_METHOD'] === "POST") {
      try {
        $animaReportRepo = new ReportAnimal();
        $reports = $animaReportRepo->fetchReportAnimal();

      }     catch (Exception $e) {

    }
  }
}
