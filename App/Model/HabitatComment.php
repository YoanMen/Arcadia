<?php

namespace App\Model;

use App\Core\Exception\DatabaseException;
use PDO;
use PDOException;

class HabitatComment extends Model
{
  protected string $table = 'habitatComment';
  private int $habitatID;
  private int $userID;

  private string $comment;

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

  /**
   * Get the value of comment
   */
  public function getComment(): string
  {
    return $this->comment;
  }

  /**
   * Set the value of comment
   */
  public function setComment(string $comment): self
  {
    $this->comment = $comment;

    return $this;
  }

  public function fetchHabitatsComment(string $order = 'ASC'): array|null
  {
    try {
      $results = null;

      if ($order !== 'ASC' && $order !== 'DESC') {
        $order = 'ASC';
      }

      $pdo = $this->connect();

      $query = "SELECT habitatComment.habitatID, habitatComment.userID,
      habitatComment.comment, habitat.name AS habitatName, user.email as userName
      FROM $this->table LEFT JOIN habitat ON habitatComment.habitatID = habitat.id 
      LEFT JOIN user ON habitatComment.userID = user.id ORDER BY habitat.name $order;";
      $stm = $pdo->prepare($query);

      if ($stm->execute()) {
        while ($result =  $stm->fetch(PDO::FETCH_ASSOC)) {
          $results[] = $result;
        }
      }

      return $results;
    } catch (PDOException $e) {
      throw new DatabaseException("Error fetchAll data: " . $e->getMessage());
    }
  }

  public function getHabitatsCommentByName(string $search): array|null
  {
    try {
      $results = null;

      $search  =  $search . '%';
      $pdo = $this->connect();
      $query = "SELECT habitatComment.habitatID, habitatComment.userID,
      habitatComment.comment, habitat.name AS habitatName, user.email as userName
      FROM $this->table LEFT JOIN habitat ON habitatComment.habitatID = habitat.id
      LEFT JOIN user ON habitatComment.userID = user.id  WHERE habitat.name LIKE :search;";

      $stm = $pdo->prepare($query);
      $stm->bindParam(':search', $search, PDO::PARAM_STR);

      if ($stm->execute()) {
        while ($result =  $stm->fetch(PDO::FETCH_ASSOC)) {
          $results[] = $result;
        }
      }

      return $results;
    } catch (PDOException $e) {
      throw new DatabaseException("Error fetchAll data: " . $e->getMessage());
    }
  }
}
