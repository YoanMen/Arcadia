<?php

namespace App\Model;

use App\Core\Exception\DatabaseException;
use DateTimeImmutable;
use Exception;
use PDO;
use PDOException;

class ReportAnimal extends Model
{
  protected string $table = 'reportAnimal';
  private int $id;

  private string $status;
  private int $animalID;
  private string $food;
  private float $weight;
  private string $date;
  private string $details;



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
   * Get the value of statut
   */
  public function getStatus(): string
  {
    return $this->status;
  }

  /**
   * Set the value of statut
   */
  public function setStatus(string $statut): self
  {
    $this->status = $statut;

    return $this;
  }

  /**
   * Get the value of animalID
   */
  public function getAnimalID(): int
  {
    return $this->animalID;
  }

  /**
   * Set the value of animalID
   */
  public function setAnimalID(int $animalID): self
  {
    $this->animalID = $animalID;

    return $this;
  }

  /**
   * Get the value of food
   */
  public function getFood(): string
  {
    return $this->food;
  }

  /**
   * Set the value of food
   */
  public function setFood(string $food): self
  {
    $this->food = $food;

    return $this;
  }

  /**
   * Get the value of weight
   */
  public function getWeight(): float
  {
    return $this->weight;
  }

  /**
   * Set the value of weight
   */
  public function setWeight(float $weight): self
  {
    $this->weight = $weight;

    return $this;
  }

  /**
   * Get the value of date
   */
  public function getDate(): string
  {
    return $this->date;
  }

  /**
   * Set the value of date
   */
  public function setDate(string $date): self
  {
    $this->date = $date;

    return $this;
  }

  /**
   * Get the value of details
   */
  public function getDetails(): string
  {
    return $this->details;
  }

  /**
   * Set the value of details
   */
  public function setDetails(string $details): self
  {
    $this->details = $details;

    return $this;
  }

  public function formatDate(string $date): string
  {
    $date = new DateTimeImmutable($date);

    return $date->format('d/m/Y');
  }

  public function fetchReportAnimal(): array|null
  {

    try {
      $results = null;
      $pdo = $this->connect();

      $query = "SELECT reportAnimal.id, animal.name as animalName, user.email as userName, reportAnimal.food,
              reportAnimal.weight, reportAnimal.date, reportAnimal.details,reportAnimal.status
              FROM $this->table LEFT JOIN animal on reportAnimal.animalID = animal.id
              LEFT JOIN user ON reportAnimal.userID = user.id ";

      $stm = $pdo->prepare($query);

      if ($stm->execute()) {
        while ($result = $stm->fetch(PDO::FETCH_ASSOC)) {
          $result['date'] = $this->formatDate($result['date']);
          $results[] = $result;
        }
      }

      return $results;
    } catch (PDOException $e) {
      throw new DatabaseException("Error fetchAll data: " . $e->getMessage());
    }
  }

  public function fetchReportAnimalByName($search): array|null
  {


    try {
      $results = null;
      $search  =  $search . '%';

      $pdo = $this->connect();

      $query = "SELECT reportAnimal.id, animal.name as animalName, user.email as userName, reportAnimal.food,
      reportAnimal.weight, reportAnimal.date, reportAnimal.details,reportAnimal.status
      FROM $this->table LEFT JOIN animal on reportAnimal.animalID = animal.id
      LEFT JOIN user ON reportAnimal.userID = user.id WHERE animal.name LIKE  :search; ";

      $stm = $pdo->prepare($query);
      $stm->bindParam(':search', $search, PDO::PARAM_STR);

      if ($stm->execute()) {
        while ($result =  $stm->fetch(PDO::FETCH_ASSOC)) {
          $result['date'] = $this->formatDate($result['date']);
          $results[] = $result;
        }
      }

      return $results;
    } catch (DatabaseException $e) {
      throw new DatabaseException("Error fetchAll data: " . $e->getMessage());
    }
  }
}
