<?php

namespace App\Model;

use App\Core\Exception\DatabaseException;
use PDO;
use PDOException;

class ReportAnimal extends Model
{
  protected string $table = 'reportAnimal';
  private int $id;

  private string $statut;
  private int $animalID;
  private int $userID;

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
  public function getStatut(): string
  {
    return $this->statut;
  }

  /**
   * Set the value of statut
   */
  public function setStatut(string $statut): self
  {
    $this->statut = $statut;

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

  public function getUserID(): int
  {
    return $this->userID;
  }

  /**
   * Set the value of animalID
   */
  public function setUserID(int $userID): self
  {
    $this->userID = $userID;

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

  /**
   * fetch report animal count depending search params
   */
  public function fetchReportAnimalCount(string $search, string $date): int | null
  {
    try {
      $search .= '%';

      if (empty($date)) {
        $date = null;
      }

      $pdo = $this->connect();
      $query = "SELECT COUNT(*)
                FROM ( SELECT reportAnimal.id, animal.name, animal.race as race,
                user.email as email, habitat.name AS habitat, animal.name as animalName,
                reportAnimal.date FROM $this->table
                INNER JOIN animal on reportAnimal.animalID = animal.id
                INNER JOIN user ON reportAnimal.userID = user.id
                INNER JOIN habitat ON habitat.id = animal.habitatID
                WHERE (:date IS NULL OR reportAnimal.date = :date)
                AND (animal.name LIKE :search OR animal.race LIKE :search
                OR user.email LIKE :search OR  habitat.name LIKE :search)) as subquery";


      $stm = $pdo->prepare($query);
      $stm->bindParam(':search', $search, PDO::PARAM_STR);
      $stm->bindParam(':date', $date, PDO::PARAM_STR);

      if ($stm->execute()) {
        $result = $stm->fetch();
      }



      return $result[0] != 0 ? $result[0] : null;
    } catch (PDOException $e) {
      throw new DatabaseException("Error count : " . $e->getMessage());
    }
  }

  /**
   * function to fetch report animal depending search params
   */
  public function fetchReportAnimal(string $search = '', string $date = '', string $order = '', string $orderBy = ''): array|null
  {
    try {
      $results = null;

      $search .=  '%';

      $orderBy = strtolower($orderBy);

      switch ($orderBy) {
        case 'De':
          $orderBy = "email";
          break;
        case 'animal':
          $orderBy = "name";
          break;
        default:
          break;
      }

      if (empty($date)) {
        $date = null;
      }

      $allowedOrderBy = ['id', 'name', 'race', 'date'];
      $allowedOrder = ['asc', 'desc'];

      $orderBy = in_array($orderBy, $allowedOrderBy) ? $orderBy : 'date';
      $order = in_array($order, $allowedOrder) ? $order : 'DESC';

      $pdo = $this->connect();
      $query = "SELECT reportAnimal.id, animal.name as name, animal.race as race, habitat.name AS habitat,
                user.email as email, reportAnimal.food,
                reportAnimal.weight, reportAnimal.date, reportAnimal.details,reportAnimal.statut
                FROM $this->table
                INNER JOIN animal on reportAnimal.animalID = animal.id
                LEFT JOIN user ON reportAnimal.userID = user.id
                LEFT JOIN habitat ON habitat.id = animal.habitatID
                WHERE (:date IS NULL OR reportAnimal.date = :date)
                AND ( animal.name LIKE :search OR user.email LIKE :search
                OR animal.race LIKE :search OR  habitat.name LIKE :search)
                ORDER BY $orderBy $order, reportAnimal.id desc LIMIT $this->limit OFFSET $this->offset;";


      $stm = $pdo->prepare($query);
      $stm->bindParam(':search', $search, PDO::PARAM_STR);
      $stm->bindParam(':date', $date, PDO::PARAM_STR);

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


  /**
   * function to fetch reports animals by id
   */
  public function fetchReportAnimalByID(int $id): array | null
  {
    try {

      $results = null;

      $pdo = $this->connect();
      $query = "SELECT reportAnimal.id, animal.name as name, animal.race as race, habitat.name AS habitat,
                user.email as email, reportAnimal.food,
                reportAnimal.weight, reportAnimal.date, reportAnimal.details,reportAnimal.statut
                FROM $this->table
                INNER JOIN animal on reportAnimal.animalID = animal.id
                LEFT JOIN user ON reportAnimal.userID = user.id
                LEFT JOIN habitat ON habitat.id = animal.habitatID
                WHERE reportAnimal.id = :id ";

      $stm = $pdo->prepare($query);
      $stm->bindParam(':id', $id, PDO::PARAM_INT);

      if ($stm->execute()) {
        while ($result =  $stm->fetch(PDO::FETCH_ASSOC)) {
          $result['date'] = $this->formatDate($result['date']);

          $results[] = $result;
        }
      }

      return $results[0];
    } catch (DatabaseException $e) {
      throw new DatabaseException("Error fetchAll data: " . $e->getMessage());
    }
  }
}
