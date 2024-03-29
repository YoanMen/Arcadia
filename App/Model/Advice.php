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


  public function getApprovedAdvices(): array | null
  {
    try {
      $results = null;

      $pdo = $this->connect();
      $query = "SELECT advice.pseudo , advice.advice, ROW_NUMBER() OVER (ORDER BY id) AS sort_id
              FROM advice
              WHERE approved = 1
              LIMIT 5";
      $stm = $pdo->prepare($query);

      if ($stm->execute()) {
        while ($result = $stm->fetch(PDO::FETCH_ASSOC)) {
          $results[] = $result;
        }
      }

      return $results;
    } catch (DatabaseException $e) {
      throw new DatabaseException("Error get advices : " . $e->getMessage());
    }
  }
  public function fetchAdvices($order, $orderBy): array | null
  {
    try {

      $results = null;


      $allowedOrderBy = ['approved'];
      $allowedOrder = ['ASC', 'DESC'];

      $orderBy = in_array($orderBy, $allowedOrderBy) ? $orderBy : 'approved';
      $order = in_array($order, $allowedOrder) ? $order : 'ASC';


      $pdo = $this->connect();
      $query = "SELECT * FROM $this->table
                ORDER BY $orderBy  $order
                LIMIT $this->limit
                OFFSET $this->offset";

      $stm = $pdo->prepare($query);

      if ($stm->execute()) {
        while ($result =  $stm->fetch(PDO::FETCH_ASSOC)) {
          $results[] = $result;
        }
      }

      return $results;
    } catch (DatabaseException $e) {
      throw new DatabaseException("Error fetchAll data: " . $e->getMessage());
    }
  }
}
