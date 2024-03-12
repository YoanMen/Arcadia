<?php

namespace App\Controller;

use App\Model\HabitatComment;

class AdminController extends Controller
{

  public function index()
  {



    $this->show('admin/dashboard');
  }
}
