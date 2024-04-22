<?php

namespace App\Model;

use App\Core\Exception\DatabaseException;
use App\Core\Database;
use DateTimeImmutable;
use PDO;
use PDOException;
use PDOStatement;

class Model extends Database
{

  protected string $table;
  protected int $limit = 10;
  protected int $offset = 0;
  protected string $orderBy = "desc";
  protected string $orderColumn = "id";

  /**
   * Get the value of limit
   */
  public function getLimit(): int
  {
    return $this->limit;
  }

  /**
   * Set the value of limit
   */
  public function setLimit(int $limit): self
  {
    $this->limit = $limit;

    return $this;
  }

  /**
   * Get the value of offset
   */
  public function getOffset(): int
  {
    return $this->offset;
  }

  /**
   * Set the value of offset
   */
  public function setOffset(int $offset): self
  {
    $this->offset = $offset;

    return $this;
  }

  /**
   * Get the value of orderBy
   */
  public function getOrderBy(): string
  {
    return $this->orderBy;
  }

  /**
   * Set the value of orderBy
   */
  public function setOrderBy(string $orderBy): self
  {
    $this->orderBy = $orderBy;

    return $this;
  }

  /**
   * Get the value of orderColumn
   */
  public function getOrderColumn(): string
  {
    return $this->orderColumn;
  }

  /**
   * Set the value of orderColumn
   */
  public function setOrderColumn(string $orderColumn): self
  {
    $this->orderColumn = $orderColumn;

    return $this;
  }



  /**
   * Fetch all
   * @param  $associative set to true for return a Associative Tab
   * @return array|null depending result
   */
  public function fetchAll(): array|null
  {

    try {
      $results = null;
      $pdo = $this->connect();
      $query = "SELECT * FROM $this->table
                ORDER BY $this->orderColumn $this->orderBy
                LIMIT $this->limit OFFSET $this->offset";

      $stm = $pdo->prepare($query);

      if ($stm->execute()) {
        while ($result = $stm->fetchObject($this::class)) {
          $results[] = $result;
        }
      }

      return $results;
    } catch (PDOException $e) {
      throw new DatabaseException("Error fetchAll data: " . $e->getMessage());
    }
  }
  /**
   * Count
   * @param array $where
   * @return int|null depending result
   */
  public function count(array $where = []): int|null
  {
    try {

      $pdo = $this->connect();
      $query = "SELECT COUNT(*) FROM $this->table";

      if (!empty($where)) {
        $keysValue = $this->setWhere($where);
        $query .= ' WHERE ' . $keysValue;
      }

      $stm = $pdo->prepare($query);

      if (!empty($where)) {
        $stm = $this->bindParams($stm, $where);
      }

      if ($stm->execute()) {
        $result = $stm->fetch();
      }

      return $result[0] != 0 ? $result[0] : null;
    } catch (PDOException $e) {
      throw new DatabaseException("Error find data: " . $e->getMessage());
    }
  }
  /**
   * Find
   * @param array $where
   * @param array|null $where_not
   * @return $this|null depending result
   */
  public function find($where, $where_not = []): array|null
  {
    try {
      $results = null;

      $keysValue = $this->setWhere($where, $where_not);

      $pdo = $this->connect();
      $query = "SELECT * FROM $this->table
                WHERE $keysValue
                ORDER BY $this->orderColumn $this->orderBy
                LIMIT $this->limit OFFSET $this->offset";

      $stm = $pdo->prepare($query);
      $stm = $this->bindParams($stm, $where, $where_not);

      if ($stm->execute()) {
        while ($result = $stm->fetchObject($this::class)) {
          $results[] = $result;
        }
      }

      return $results;
    } catch (PDOException $e) {
      throw new DatabaseException("Error find data: " . $e->getMessage());
    }
  }
  /**
   * Find One
   * @param array $where
   * @param array|null $where_not
   * @return $this|null depending result
   */
  public function findOneBy($where, $where_not = []): object|null
  {
    try {
      $result = null;

      $keysValue = $this->setWhere($where, $where_not);
      $pdo = $this->connect();
      $query = "SELECT * FROM $this->table
                WHERE $keysValue
                ORDER BY $this->orderColumn $this->orderBy
                LIMIT $this->limit OFFSET $this->offset";

      $stm = $pdo->prepare($query);
      $stm = $this->bindParams($stm, $where, $where_not);

      if ($stm->execute()) {
        $result = $stm->fetchObject($this::class);
      }

      return is_bool($result) ? null : $result;
    } catch (PDOException $e) {
      throw new DatabaseException("Error findOneBy data: " . $e->getMessage());
    }
  }
  /**
   * Insert
   * @param array $data
   */
  public function insert($data)
  {
    try {
      $keysValue = $this->setInsert($data);

      $pdo = $this->connect();
      $query = "INSERT INTO $this->table $keysValue";
      $stm = $pdo->prepare($query);
      $stm = $this->bindParams($stm, $data);
      $stm->execute();
    } catch (PDOException $e) {
      throw new DatabaseException("Error inserting data: " . $e->getMessage());
    }
  }
  /**
   * Update
   * @param array $data to be updated
   * @param int $id of element to be updated
   */
  public function update(array $data, int $id)
  {
    try {
      $keysValue = $this->setUpdate($data);
      $keysValueWhere = $this->setWhere(['id' => $id]);
      $pdo = $this->connect();
      $query = "UPDATE  $this->table SET $keysValue WHERE $keysValueWhere ";

      $stm = $pdo->prepare($query);
      $stm = $this->bindParams($stm, $data);
      $stm = $this->bindParams($stm, ['id' => $id]);
      $stm->execute();
    } catch (PDOException $e) {
      throw new DatabaseException("Error Updating data: " . $e->getMessage());
    }
  }
  /**
   * Delete
   * @param array $where
   */
  public function delete(array $where)
  {
    try {
      $keysValue = $this->setWhere($where);

      $pdo = $this->connect();
      $query = "DELETE FROM $this->table WHERE $keysValue";
      $stm = $pdo->prepare($query);
      $stm = $this->bindParams($stm, $where);
      $stm->execute();
    } catch (PDOException $e) {
      throw new DatabaseException("Error delete data: " . $e->getMessage());
    }
  }
  /**
   * Set param of PDO depending of $input type
   * @param  $input
   * @return int of PDO::PARAM
   */
  private function setParamTypeForPDO($input)
  {
    if (is_bool($input)) {
      return PDO::PARAM_BOOL;
    } elseif (is_int($input)) {
      return PDO::PARAM_INT;
    } elseif (is_string($input)) {
      return PDO::PARAM_STR;
    }

    return PDO::PARAM_NULL;
  }
  /**
   * Set param of PDO depending of $input type
   * @param  $stm need a PDOStatement
   * @param  $where array of WHERE
   * @param  $where_not array of WHERE NOT
   * @return PDOStatement
   */
  protected function bindParams($stm, $where, $where_not = []): PDOStatement
  {
    foreach (array_keys($where) as $key) {
      $stm->bindParam($key, $where[$key], $this->setParamTypeForPDO($where[$key]) ?? PDo::PARAM_NULL);
    }
    foreach (array_keys($where_not) as $key) {
      $stm->bindParam($key, $where_not[$key], $this->setParamTypeForPDO($where_not[$key]) ?? PDo::PARAM_NULL);
    }

    return $stm;
  }
  /**
   * Set key for WHERE/NOT WHERE
   * @param  $where  WHERE
   * @param  $whereNot WHERE NOT
   * @return string
   */
  protected function setWhere($where, $whereNot = []): string
  {
    $keysValue = '';

    foreach (array_keys($where) as $key) {
      $keysValue .= "$key = :$key AND ";
    }
    foreach (array_keys($whereNot) as $key) {
      $keysValue .= "$key != :$key AND ";
    }
    $keysValue = rtrim($keysValue, ' AND ');
    return $keysValue;
  }
  /**
   * Set key for Insert
   * @param  $data of keys
   * @return string
   */
  protected function setInsert($data): string
  {
    $insertKeys = '(';

    foreach (array_keys($data) as $key) {
      $insertKeys .= "$key , ";
    }
    $insertKeys = rtrim($insertKeys, ' , ');

    $insertKeys .= ') VALUES (';
    foreach (array_keys($data) as $key) {
      $insertKeys .= ":$key , ";
    }

    $insertKeys = rtrim($insertKeys, ' , ');
    $insertKeys .= ')';

    return $insertKeys;
  }
  /**
   * Set key for Update
   * @param  $data of keys
   * @return string
   */
  protected function setUpdate($data)
  {
    $keysValue = '';

    foreach (array_keys($data) as $key) {
      $keysValue .= "$key = :$key , ";
    }

    $keysValue = rtrim($keysValue, ' , ');

    return $keysValue;
  }

  /**
   * @param  $date string date
   * @return string date format 'd/m/Y'
   */
  public function formatDate(string $date): string
  {
    $date = new DateTimeImmutable($date);

    return $date->format('d/m/Y');
  }
}
