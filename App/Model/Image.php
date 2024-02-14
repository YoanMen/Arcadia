<?php
namespace App\Model;

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
}

