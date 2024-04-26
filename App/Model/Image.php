<?php

namespace App\Model;

use App\Core\Exception\DatabaseException;
use App\Core\UploadFile;

class Image extends Model
{
  protected string $table = 'image';

  private int $id;
  private string $path;

  /**
   * Get the value of id
   */
  public function getId(): int
  {
    return $this->id;
  }

  /**
   * Set the value of id
   */
  public function setId(int $id): self
  {
    $this->id = $id;

    return $this;
  }



  /**
   * Get the value of path
   */
  public function getPath(): string
  {
    return $this->path;
  }

  /**
   * Set the value of path
   */
  public function setPath(string $path): self
  {
    $this->path = $path;

    return $this;
  }

  /**
   * function to delete image on the server and database
   */
  public function deleteImage(int $id)
  {
    $imageRepo = new Image();
    $image = $imageRepo->findOneBy(['id' => $id]);

    if ($image) {
      UploadFile::remove($image->getPath());
      $imageRepo->delete(['id' => $id]);

      $_SESSION['success'] = "image supprimé";
      echo json_encode(['success' => 'image supprimé']);
    } else {
      http_response_code(201);
      $_SESSION['error'] = "impossible de récupéré l'image ";
      throw new DatabaseException('impossible de récupéré l\'image ');
    }
  }
}
