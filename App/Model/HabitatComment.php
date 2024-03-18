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

  public function habitatsCommentCount(string $search): int |null
  {
    try {
      $search .= '%';

      $pdo = $this->connect();

      $query = "SELECT COUNT(habitatComment.habitatID) AS total_count
                FROM habitatComment
                LEFT JOIN habitat ON habitatComment.habitatID = habitat.id
                WHERE habitat.name LIKE :search";


      $stm = $pdo->prepare($query);
      $stm->bindParam(':search', $search, PDO::PARAM_STR);

      if ($stm->execute()) {
        $result = $stm->fetch();
      }
    } catch (PDOException $e) {
      throw new DatabaseException("Error count : " . $e->getMessage());
    }

    return ($result[0] != 0 ? $result[0] : null);
  }
  public function fetchHabitatsComment(string $search, string $order, string $orderBy): array|null
  {
    try {
      $results = null;

      $search .=  '%';

      if ($order !== 'ASC' && $order !== 'DESC') {
        $order = 'DESC';
      }

      $allowedOrderBy = ['id', 'habitatName'];
      $allowedOrder = ['ASC', 'DESC'];

      $orderBy = in_array($orderBy, $allowedOrderBy) ? $orderBy : 'id';
      $order = in_array($order, $allowedOrder) ? $order : 'ASC';

      $pdo = $this->connect();
      $query = "SELECT habitatComment.habitatID AS id,
                habitatComment.comment,
                habitat.name AS habitatName,
                user.email AS userName
                FROM habitatComment
                LEFT JOIN habitat ON habitatComment.habitatID = habitat.id
                LEFT JOIN user ON habitatComment.userID = user.id
                WHERE habitat.name LIKE :search
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
}
