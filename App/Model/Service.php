<?php

namespace App\Model;

use App\Core\Exception\DatabaseException;
use PDO;

class Service extends Model
{
  protected string $table = 'service';
  private int $id;
  private string $name;
  private string $description;


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
   * Get the value of description
   */
  public function getDescription(): string
  {
    return $this->description;
  }

  /**
   * Set the value of description
   */
  public function setDescription(string $description): self
  {
    $this->description = $description;

    return $this;
  }

  public function servicesCount(string $search)
  {
    try {
      $search .= '%';

      $pdo = $this->connect();

      $query = 'SELECT COUNT(service.id) AS total_count
                FROM service
                WHERE service.name LIKE :search';

      $stm = $pdo->prepare($query);
      $stm->bindParam(':search', $search, PDO::PARAM_STR);

      if ($stm->execute()) {
        $result = $stm->fetch();
      }

      return ($result[0] != null ? $result[0] : null);
    } catch (DatabaseException $e) {
      throw new DatabaseException('Error count : ' . $e->getMessage());
    }
  }

  public function fetchServices(string $search, string $order, string $orderBy)
  {
    try {

      $results = null;

      $search .=  '%';

      if ($orderBy == 'Nom') {
        $orderBy = 'name';
      }

      $allowedOrderBy = ['id', 'name'];
      $allowedOrder = ['asc', 'desc'];

      $orderBy = in_array($orderBy, $allowedOrderBy) ? $orderBy : 'id';
      $order = in_array($order, $allowedOrder) ? $order : 'ASC';


      $pdo = $this->connect();
      $query = "SELECT * FROM $this->table WHERE service.name LIKE :search
      ORDER BY $orderBy  $order
      LIMIT $this->limit OFFSET $this->offset";

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
