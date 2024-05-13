<?php

namespace App\Model;

use App\Core\Exception\DatabaseException;
use PDO;
use PDOException;

class HabitatComment extends Model
{
  protected string $orderColumn = "habitatId";

  protected string $table = 'habitatComment';
  private int $habitatID;
  private int $userID;

  private string $comment;

  private string $updated_at;

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

  /**
   * function to update comment of habitat
   */
  public function updateComment(int $habitatId, int $userId, string $comment)
  {
    try {
      $pdo = $this->connect();

      $query = "UPDATE $this->table
                SET userId = :userId, comment = :comment, updated_at = CURRENT_TIMESTAMP
                WHERE habitatId = :habitatId; ";

      $stm = $pdo->prepare($query);

      $stm->bindParam(':comment', $comment, PDO::PARAM_STR);
      $stm->bindParam(':habitatId', $habitatId, PDO::PARAM_INT);
      $stm->bindParam(':userId', $userId, PDO::PARAM_INT);


      $stm->execute();
    } catch (PDOException $e) {
      throw new DatabaseException("Error update data: " . $e->getMessage());
    }
  }

  /**
   * function to get count of habitat comment depending search
   */
  public function countHabitatComment(string $search): int | null
  {

    try {
      $search .= "%";

      $pdo = $this->connect();
      $query = "SELECT COUNT(*)
                FROM ( SELECT habitat.name  as habitat, user.email , habitatComment.habitatID,
                habitatComment.userID, habitatComment.comment FROM $this->table
                INNER JOIN habitat ON habitatComment.habitatID = habitat.id
                LEFT JOIN user ON habitatComment.userID = user.id
                WHERE habitat.name LIKE :search OR user.email LIKE :search ) as subquery";

      $stm = $pdo->prepare($query);
      $stm->bindParam(':search', $search, PDO::PARAM_STR);

      if ($stm->execute()) {
        $result = $stm->fetch();
      }

      return $result[0] != 0 ? $result[0] : null;
    } catch (PDOException $e) {
      throw new DatabaseException("Error count : " . $e->getMessage());
    }
  }

  /**
   * function to fetch habitats comment with search params
   */
  public function fetchHabitatsComment(string $search = '', string $order = '', string $orderBy = ''): array|null
  {
    try {
      $results = null;

      $search .=  '%';

      if ($order !== 'asc' && $order !== 'desc') {
        $order = 'desc';
      }

      $search = strtolower($search);

      if ($search == 'de') {
        $search = 'email';
      }

      $allowedOrderBy = ['habitat, email'];
      $allowedOrder = ['asc', 'desc'];

      $orderBy = in_array($orderBy, $allowedOrderBy) ? $orderBy : 'habitat';
      $order = in_array($order, $allowedOrder) ? $order : 'asc';

      $pdo = $this->connect();
      $query = "SELECT habitatComment.habitatID AS id,
                habitatComment.comment,
                habitat.name AS habitat, user.email
                FROM habitatComment
                INNER JOIN habitat ON habitatComment.habitatID = habitat.id
                LEFT JOIN user ON habitatComment.userID = user.id
                WHERE habitat.name LIKE :search OR user.email LIKE :search
                ORDER BY $orderBy  $order
                LIMIT $this->limit OFFSET $this->offset";

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

  /**
   * function to find habitat comment with id
   */
  public function findHabitatCommentById($id): array | null
  {
    try {

      $result = null;

      $pdo = $this->connect();

      $query = "SELECT habitat.name as habitat, user.email, habitatComment.comment, habitatComment.updated_at
              FROM $this->table
              INNER JOIN habitat ON habitat.id = habitatComment.habitatID
              LEFT JOIN user ON user.id = habitatComment.userID
              WHERE habitatComment.habitatID = :id;";

      $stm = $pdo->prepare($query);
      $stm->bindParam(':id', $id, PDO::PARAM_INT);

      if ($stm->execute()) {
        $result = $stm->fetch();
      }

      return ($result != null ? $result : null);
    } catch (DatabaseException $e) {
      throw new DatabaseException("Error count : " . $e->getMessage());
    }
  }

  /**
   * Get the value of updated_at
   */
  public function getUpdatedAt(): string
  {
    return $this->updated_at;
  }

  /**
   * Set the value of updated_at
   */
  public function setUpdatedAt(string $updated_at): self
  {
    $this->updated_at = $updated_at;

    return $this;
  }
}
