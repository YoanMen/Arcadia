<?php

namespace App\Model;

use App\Core\Exception\DatabaseException;
use PDO;

class FoodAnimal extends Model
{
  protected string $table = 'foodAnimal';
  private int $id;
  private int $userID;

  private int $animalID;

  private string $food;
  private float $quantity;
  private string $time;
  private string $date;

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
   * Get the value of userID
   */
  public function getUserID(): int
  {
    return $this->userID;
  }

  /**
   * Set the value of userID
   */
  public function setUserID(int $userID): self
  {
    $this->userID = $userID;

    return $this;
  }

  public function getAnimalID(): int
  {
    return $this->userID;
  }

  /**
   * Set the value of userID
   */
  public function setAnimalID(int $userID): self
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
   * Get the value of quantity
   */
  public function getQuantity(): float
  {
    return $this->quantity;
  }

  /**
   * Set the value of quantity
   */
  public function setQuantity(float $quantity): self
  {
    $this->quantity = $quantity;

    return $this;
  }

  /**
   * Get the value of time
   */
  public function getTime(): string
  {
    return $this->time;
  }

  /**
   * Set the value of time
   */
  public function setTime(string $time): self
  {
    $this->time = $time;

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

  public function foodAnimalsCount($search): int | null
  {
    try {
      $search .= '%';

      $pdo = $this->connect();
      $query = "SELECT COUNT(foodAnimal.id) AS total_count
                FROM foodAnimal
                INNER JOIN animal ON animal.id = foodAnimal.animalID
                INNER JOIN habitat ON habitat.id = animal.habitatID
                WHERE habitat.name LIKE :search OR animal.name LIKE :search;";

      $stm = $pdo->prepare($query);
      $stm->bindParam(':search', $search, PDO::PARAM_STR);

      if ($stm->execute()) {
        $result = $stm->fetch();
      }

      return ($result[0] != null ? $result[0] : null);
    } catch (DatabaseException $e) {
      throw new DatabaseException("Error count : " . $e->getMessage());
    }
  }

  public function fetchFoodAnimalsByUser(string $search, int $userId, string $order, string $orderBy): array | null
  {
    try {

      $results = null;

      $search .=  '%';

      $allowedOrderBy = ['id', 'name', 'habitat', 'date'];
      $allowedOrder = ['ASC', 'DESC'];

      $orderBy = in_array($orderBy, $allowedOrderBy) ? $orderBy : 'id';
      $order = in_array($order, $allowedOrder) ? $order : 'ASC';


      $pdo = $this->connect();
      $query = "SELECT foodAnimal.id, foodAnimal.userID, animal.name,
                habitat.name AS habitat, foodAnimal.food, foodAnimal.quantity,
                foodAnimal.time, foodAnimal.date
                FROM foodAnimal
                INNER JOIN animal ON animal.id = foodAnimal.animalID
                INNER JOIN habitat ON habitat.id = animal.habitatID
                WHERE  foodAnimal.userID = :userId AND habitat.name LIKE :search OR animal.name LIKE :search
                AND foodAnimal.userID = :userId
                ORDER BY $orderBy  $order
                LIMIT $this->limit OFFSET $this->offset";

      $stm = $pdo->prepare($query);
      $stm->bindParam(':search', $search, PDO::PARAM_STR);
      $stm->bindParam(':userId', $userId, PDO::PARAM_INT);

      if ($stm->execute()) {
        while ($result =  $stm->fetch(PDO::FETCH_ASSOC)) {
          $result['date'] = $this->formatDate($result['date']);

          $time = date_create($result['time']);
          $result['time'] = date_format($time, "H\hi");

          $results[] = $result;
        }
      }

      return $results;
    } catch (DatabaseException $e) {
      throw new DatabaseException("Error fetchAll data: " . $e->getMessage());
    }
  }
}
