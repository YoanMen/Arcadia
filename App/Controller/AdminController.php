<?php

namespace App\Controller;

class AdminController extends Controller
{

  public function index()
  {
    $this->show('admin/dashboard');
  }
}
