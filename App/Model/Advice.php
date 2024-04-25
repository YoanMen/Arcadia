<?php

namespace App\Model;

use App\Core\Exception\DatabaseException;
use PDO;

class Advice extends Model
{
  protected string $table = 'advice';
  private int $id;
  private string $pseudo;

  private string $advice;

  private bool $approved;


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
   * Get the value of pseudo
   */
  public function getPseudo(): string
  {
    return $this->pseudo;
  }

  /**
   * Set the value of pseudo
   */
  public function sePseudo(string $pseudo): self
  {
    $this->pseudo = $pseudo;

    return $this;
  }

  /**
   * Get the value of advice
   */
  public function getAdvice(): string
  {
    return $this->advice;
  }

  /**
   * Set the value of advice
   */
  public function setAdvice(string $advice): self
  {
    $this->advice = $advice;

    return $this;
  }

  /**
   * Get the value of approved
   */
  public function getApproved(): bool
  {
    return $this->approved;
  }

  /**
   * Set the value of approved
   */
  public function setApproved(bool $approved): self
  {
    $this->approved = $approved;

    return $this;
  }

  public function adviceCount($search)
  {
    try {
      $search .= '%';

      $pdo = $this->connect();
      $query = "SELECT COUNT(advice.id) as total_count
                FROM $this->table
                WHERE advice.pseudo LIKE :search";

      $stm = $pdo->prepare($query);
      $stm->bindParam(':search', $search, PDO::PARAM_STR);

      if ($stm->execute()) {
        $result = $stm->fetch();
      }

      return ($result[0] != null) ? $result[0] : null;
    } catch (DatabaseException $e) {
      throw new DatabaseException('Error count :' . $e->getMessage());
    }
  }

  public function approvedAdviceCount()
  {
    try {

      $pdo = $this->connect();
      $query = "SELECT COUNT(advice.id) as total_count
                FROM $this->table
                WHERE advice.approved = 1";

      $stm = $pdo->prepare($query);

      if ($stm->execute()) {
        $result = $stm->fetch();
      }

      return ($result[0] != null) ? $result[0] : null;
    } catch (DatabaseException $e) {
      throw new DatabaseException('Error count :' . $e->getMessage());
    }
  }


  public function getApprovedAdvice(int $id): array | bool
  {
    try {
      $result = null;

      $pdo = $this->connect();
      $query = "SELECT * FROM ( SELECT advice.pseudo, advice.advice,
                ROW_NUMBER() OVER (ORDER BY advice.id DESC) AS id
                FROM $this->table
                WHERE approved = 1) AS numbered_rows
                WHERE id = :id;";

      $stm = $pdo->prepare($query);
      $stm->bindParam(':id', $id, PDO::PARAM_INT);

      if ($stm->execute()) {
        $result =  $stm->fetch(PDO::FETCH_ASSOC);
      }

      return $result;
    } catch (DatabaseException $e) {
      throw new DatabaseException("Error get advices : " . $e->getMessage());
    }
  }
  public function fetchAdvices(string $search, string $order, string $orderBy): array | null
  {
    try {

      $results = null;

      $search .= '%';

      $orderBy = strtolower($orderBy);

      if ($orderBy == 'approuvé') {
        $orderBy = 'approved';
      }
      $allowedOrderBy = ['id', 'approved', 'pseudo'];
      $allowedOrder = ['asc', 'desc'];

      $orderBy = in_array($orderBy, $allowedOrderBy) ? $orderBy : 'id';
      $order = in_array($order, $allowedOrder) ? $order : 'asc';

      $pdo = $this->connect();
      $query = "SELECT * FROM $this->table
                WHERE advice.pseudo LIKE :search
                ORDER BY $orderBy  $order
                LIMIT $this->limit
                OFFSET $this->offset";

      $stm = $pdo->prepare($query);
      $stm->bindParam(':search', $search, PDO::PARAM_STR);

      if ($stm->execute()) {
        while ($result =  $stm->fetchObject($this::class)) {
          $results[] = $result;
        }
      }

      return $results;
    } catch (DatabaseException $e) {
      throw new DatabaseException("Error fetchAll data: " . $e->getMessage());
    }
  }
}
