<?php
namespace App\Model;

use App\Core\Exception\DatabaseException;
use PDO, PDOException;

class Animal extends Model
{
  protected string $table = 'animal';

  private int $id;

  private string $name;
  private string $race;
  private int $habitatID;


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
   * Get the value of race
   */
  public function getRace(): string
  {
    return $this->race;
  }

  /**
   * Set the value of race
   */
  public function setRace(string $race): self
  {
    $this->race = $race;

    return $this;
  }

  /**
   * Get the value of habitatID
   */
  public function getHabitatID(): int
  {
    return $this->habitatID;
  }

  /**
   * Set the value of habitatID
   */
  public function setHabitatID(int $habitatID): self
  {
    $this->habitatID = $habitatID;

    return $this;
  }

  public function findImages(): array|null
  {
    try {

      $results = null;

      $pdo = $this->connect();
      $query = "SELECT image.id , image.path FROM animal_image
      INNER JOIN image ON animal_image.imageID = image.id
      WHERE animal_image.animalID = :animalID;";

      $stm = $pdo->prepare($query);
      $stm = $this->bindParams($stm, [':animalID' => $this->getId()]);
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
}