<?php

namespace App\Model;

use App\Core\Exception\DatabaseException;
use PDO;

class TryConnection extends Model
{

  protected string $table = 'tryConnection';

  private int $userId;
  private int $count;



  /**
   * Get the value of userId
   */
  public function getUserId(): int
  {
    return $this->userId;
  }

  /**
   * Set the value of userId
   */
  public function setUserId(int $userId): self
  {
    $this->userId = $userId;

    return $this;
  }

  /**
   * Get the value of count
   */
  public function getCount(): int
  {
    return $this->count;
  }

  /**
   * Set the value of count
   */
  public function setCount(int $count): self
  {
    $this->count = $count;

    return $this;
  }

  public function findByUserId(int $userID): TryConnection | null
  {
    try {
      $pdo = $this->connect();

      $query = "SELECT * FROM tryConnection WHERE user_id = :id ;";

      $stm = $pdo->prepare($query);
      $stm->bindParam(':id', $userID, PDO::PARAM_INT);

      if ($stm->execute()) {
        $result = $stm->fetchObject($this::class);
      }

      return is_bool($result) ? null : $result;
    } catch (DatabaseException $e) {
      throw new DatabaseException("Error " . $e);
    }
  }
}
