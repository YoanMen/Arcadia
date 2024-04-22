<?php

namespace App\Model;

use App\Core\Database;
use PDO, PDOException;
use App\Core\Exception\DatabaseException;
use App\Core\UploadFile;

class Habitat extends Model
{
  protected string $table = 'habitat';

  private int $id;
  private string $name;
  private string $description;

  private array $images;
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

  /**
   * Get image
   * @param $number to select image in array
   * @return Image or null if dont have image
   */
  public function getImage(int $number): Image|null
  {
    return $this->images[$number] ?? null;
  }

  public function getAllImagePath(): array|null
  {
    $imagesPath = [];

    foreach ($this->images as $image) {
      $imagesPath[] = $image->getPath();
    }

    return $imagesPath ?? null;
  }

  /**
   * Set Image to class
   */
  public function setImage(Image $image)
  {
    $this->images[] = $image;
  }


  /**
   * Find images for one Habitat
   */
  public function findImages(): array|null
  {
    try {

      $results = null;

      $pdo = $this->connect();
      $query = "SELECT habitat_image.habitatID ,image.id, image.path FROM habitat_image
      LEFT JOIN image ON habitat_image.imageID = image.id
      WHERE habitat_image.habitatID = :habitatID;";

      $stm = $pdo->prepare($query);
      $stm = $this->bindParams($stm, [':habitatID' => $this->getId()]);
      if ($stm->execute()) {
        while ($result = $stm->fetch(PDO::FETCH_ASSOC)) {

          $this->setImage(new Image($result['id'], $result['path']));
          $results[] = $result;
        }
      }

      return $results;
    } catch (PDOException $e) {
      throw new DatabaseException("Error findImages habitat : " . $e->getMessage(), $e->getCode(), $e);
    }
  }
  /**
   * Find all animals on habitat
   */

  public function habitatsCount($search): int | null
  {
    try {
      $search .= '%';

      $pdo = $this->connect();
      $query = "SELECT COUNT(habitat.id) AS total_count
      FROM habitat
      WHERE habitat.name LIKE :search";

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

  public function fetchImages($id)
  {
    try {

      $results = null;

      $pdo = $this->connect();
      $query = "SELECT image.id, image.path FROM habitat_image
      LEFT JOIN image ON habitat_image.imageID = image.id
      WHERE habitat_image.habitatID = :habitatID;";

      $stm = $pdo->prepare($query);
      $stm->bindParam(':habitatID', $id, PDO::PARAM_INT);

      if ($stm->execute()) {
        while ($result = $stm->fetch(PDO::FETCH_ASSOC)) {
          $results[] = $result;
        }
      }

      return $results;
    } catch (PDOException $e) {
      throw new DatabaseException("Error findImages habitat : " . $e->getMessage(), $e->getCode(), $e);
    }
  }

  public function fetchMenuHabitat(): array
  {
    try {
      $pdo = $this->connect();
      $query = "SELECT habitat.name, habitat.id FROM $this->table
                LIMIT 5";

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

  public function fetchHabitats(string $search, string $order, string $orderBy): array | null
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
      $query = "SELECT * FROM $this->table WHERE habitat.name LIKE :search
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

  public function insertImage(int $habitatId, int $imageId): bool|null
  {

    try {

      $pdo = $this->connect();
      $query = "INSERT INTO `habitat_image`(`habitatID`, `imageID`) VALUES (:habitatId , :imageId)";

      $stm = $pdo->prepare($query);
      $stm->bindParam(':habitatId', $habitatId, PDO::PARAM_INT);
      $stm->bindParam(':imageId', $imageId, PDO::PARAM_INT);

      $stm->execute();

      return true;
    } catch (PDOException $e) {
      throw new DatabaseException("Error to insert habitat_image : " . $e->getMessage());
    }
  }

  public function fetchAllHabitatsWithoutComment(): array | null
  {
    try {
      $results = null;

      $pdo = $this->connect();
      $query = "SELECT habitat.id, habitat.name from habitat";

      $stm = $pdo->prepare($query);
      $stm->execute();

      if ($stm->execute()) {
        while ($result = $stm->fetch(PDO::FETCH_ASSOC)) {
          $results[] = $result;
        }
      }

      return $results;
    } catch (PDOException $e) {
      throw new DatabaseException("Error to fetch : " . $e->getMessage());
    }
  }

  public function addImage($id)
  {
    $path =  UploadFile::upload();
    $imageRepo = new Image();

    $imageRepo->insert(['path' => $path]);
    $image = $imageRepo->findOneBy(['path' => $path]);

    $this->insertImage($id, $image->getId());

    echo json_encode(['path' => $image->getPath(), 'id' =>  $image->getId()]);
  }
}
