<?php

namespace App\Controller;

use App\Core\Exception\FileException;
use App\Core\Security;
use App\Core\UploadFile;
use App\Model\Image;
use Exception;

class UploadController extends Controller
{

  public function index()
  {
    $this->show("upload");
  }

  public function uploadFile()
  {
    $csrf = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
    if (Security::verifyCsrf($csrf) && Security::isAdmin() && $_SERVER['REQUEST_METHOD'] === 'POST') {
      try {
        UploadFile::upload();
      } catch (Exception $e) {
        throw new FileException("Error with file Upload : " . $e->getMessage(), $e->getCode(), $e);
      }
    } else {
      http_response_code(401);
      echo json_encode(['error' => 'CSRF token is not valid']);
    }
  }
}
