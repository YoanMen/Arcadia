<?php
namespace App\Model;

use App\Core\Database;
use PDO, PDOException;
use App\Core\Exception\DatabaseException;

class Habitat extends Model
{
  protected string $table = 'habitat';

  private int $id;
  private string $name;
  private string $description;

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
   * Get the value of name
   */
  public function getName(): string
  {
    return $this->name;
  }

  /**
   * Set the value of name
   */
  public function setName(string $name): self
  {
    $this->name = $name;

    return $this;
  }

  /**
   * Get the value of description
   */
  public function getDescription(): string
  {
    return $this->description;
  }

  /**
   * Set the value of description
   */
  public function setDescription(string $description): self
  {
    $this->description = $description;

    return $this;
  }


  /**
   * Find images for Habitat
   */
  public function findImages(): array|null
  {
    try {

      $results = null;

      $pdo = $this->connect();
      $query = "SELECT image.id, image.path FROM habitat_image
      INNER JOIN image ON habitat_image.imageID = image.id
      WHERE habitat_image.habitatID = :habitatID;";

      $stm = $pdo->prepare($query);
      $stm = $this->bindParams($stm, [':habitatID' => $this->getId()]);
      if ($stm->execute()) {
        while ($result = $stm->fetchObject(Image::class)) {
          $results[] = $result;
        }
      }

      return $results;
    } catch (PDOException $e) {
      throw new DatabaseException("Error findImages habitat : " . $e->getMessage(), $e->getCode(), $e);
    }
  }


  /**
   * Find all animals
   */
  public function findAllAnimals(): array|null
  {
    try {

      $result = null;
      $habitatID = $this->getId();

      $pdo = $this->connect();
      $query = "SELECT animal.id as id, animal.name, animal.race FROM habitat
                INNER JOIN animal ON habitat.id = animal.habitatID
                WHERE habitat.id = $habitatID;";

      $stm = $pdo->prepare($query);

      if ($stm->execute()) {
        while ($result = $stm->fetchObject(Animal::class)) {
          $results[] = $result;
        }
      }

      return $results;
    } catch (PDOException $e) {
      throw new DatabaseException("Error findAllAnimals : " . $e->getMessage(), $e->getCode(), $e);
    }
  }


}