<?php

namespace App\Model;

use App\Core\Exception\DatabaseException;
use PDO, PDOException;

class Animal extends Model
{
  protected string $table = 'animal';

  private int $id;

  private string $name;
  private string $race;
  private int $habitatID;

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
   * Get the value of race
   */
  public function getRace(): string
  {
    return $this->race;
  }

  /**
   * Set the value of race
   */
  public function setRace(string $race): self
  {
    $this->race = $race;

    return $this;
  }

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
  public function getImage(int $number): Image|null
  {
    return $this->images[$number] ?? null;
  }

  /**
   * Set Image to class
   */
  public function setImage(Image $image)
  {
    $this->images[] = $image;
  }

  public function getAllImagePath(): array|null
  {
    $imagesPath = [];

    foreach ($this->images as $image) {
      $imagesPath[] = $image->getPath();
    }
    return $imagesPath ?? null;
  }
  public function findImages(): array|null
  {
    try {

      $results = null;

      $pdo = $this->connect();
      $query = "SELECT image.id , image.path FROM animal_image
      INNER JOIN image ON animal_image.imageID = image.id
      WHERE animal_image.animalID = :animalID;";

      $stm = $pdo->prepare($query);
      $stm = $this->bindParams($stm, [':animalID' => $this->getId()]);
      if ($stm->execute()) {
        while ($result = $stm->fetchObject(Image::class)) {
          $this->setImage($result);
          $results[] = $result;
        }
      }

      return $results;
    } catch (PDOException $e) {
      throw new DatabaseException("Error findImages habitat : " . $e->getMessage(), $e->getCode(), $e);
    }
  }

  public function insertImage(int $animalId, int $imageId): bool|null
  {

    try {

      $pdo = $this->connect();
      $query = "INSERT INTO `animal_image`(`animalID`, `imageID`) VALUES (:animalId , :imageId)";

      $stm = $pdo->prepare($query);
      $stm->bindParam(':animalId', $animalId, PDO::PARAM_INT);
      $stm->bindParam(':imageId', $imageId, PDO::PARAM_INT);

      $stm->execute();

      return true;
    } catch (PDOException $e) {
      throw new DatabaseException("Error to insert animal_image : " . $e->getMessage());
    }
  }

  public function animalsCount($search): int|null
  {
    try {
      $search .= '%';

      $pdo = $this->connect();

      $query = "SELECT COUNT(animal.id) as total_count
                FROM animal
                WHERE (animal.name LIKE :search OR animal.race LIKE :search)";

      $stm = $pdo->prepare($query);
      $stm->bindParam(':search', $search, PDO::PARAM_STR);

      if ($stm->execute()) {
        $result = $stm->fetch();
      }

      return ($result[0] != null) ? $result[0] : null;
    } catch (DatabaseException $e) {
      throw new DatabaseException("Error count : " . $e->getMessage());
    }
  }


  public function fetchImages($id)
  {
    try {

      $results = null;

      $pdo = $this->connect();
      $query = "SELECT image.id, image.path FROM animal_image
      LEFT JOIN image ON animal_image.imageID = image.id
      WHERE animal_image.animalID = :animalID;";

      $stm = $pdo->prepare($query);
      $stm->bindParam(':animalID', $id, PDO::PARAM_INT);

      if ($stm->execute()) {
        while ($result = $stm->fetch(PDO::FETCH_ASSOC)) {
          $results[] = $result;
        }
      }

      return $results;
    } catch (PDOException $e) {
      throw new DatabaseException("Error findImages animal : " . $e->getMessage(), $e->getCode(), $e);
    }
  }
  public function fetchAnimals(string $search, string $order, string $orderBy): array|null
  {
    try {

      $results = null;

      $search .=  '%';

      $allowedOrderBy = ['id', 'name', 'race',  'habitat'];
      $allowedOrder = ['ASC', 'DESC'];

      $orderBy = in_array($orderBy, $allowedOrderBy) ? $orderBy : 'id';
      $order = in_array($order, $allowedOrder) ? $order : 'ASC';

      $pdo = $this->connect();
      $query = "SELECT animal.id, animal.name, animal.race , habitat.name as habitat
                FROM animal INNER JOIN habitat ON habitat.id = animal.habitatID
                WHERE animal.race LIKE :search OR animal.name LIKE :search
                ORDER BY $orderBy  $order LIMIT $this->limit OFFSET $this->offset";


      $stm = $pdo->prepare($query);
      $stm->bindParam(':search', $search, PDO::PARAM_STR);

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

  public function fetchAnimalsByHabitat(string $id): array|null
  {
    try {

      $results = null;

      $pdo = $this->connect();
      $query = "SELECT animal.id, animal.name, animal.race
                FROM animal
                WHERE animal.habitatID = :id";


      $stm = $pdo->prepare($query);
      $stm->bindParam(':id', $id, PDO::PARAM_INT);

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
