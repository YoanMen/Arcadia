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
  private ?int $habitatID;

  private ?string $habitat;

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


  public function getHabitat(): ?string
  {
    return $this->habitat ?? 'aucun habitat';
  }

  public function getHabitatID(): ?int
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

  /**
   * Find all image for this animal
   * @return array of Image or null
   */
  public function findImagesForThisAnimal(): array|null
  {
    try {
      $results = null;

      $pdo = $this->connect();
      $query = "SELECT image.id , image.path
                FROM animal_image
                INNER JOIN image ON animal_image.imageID = image.id
                WHERE animal_image.animalID = :animalID;";

      $stm = $pdo->prepare($query);

      $stm = $this->bindParams($stm, [':animalID' => $this->getId()]);

      if ($stm->execute()) {
        while ($result = $stm->fetch(PDO::FETCH_ASSOC)) {

          $image = new Image();

          $image->setId($result['id']);
          $image->setPath($result['path']);
          $this->setImage($image);

          $results[] = $result;
        }
      }

      return $results ?? null;
    } catch (PDOException $e) {
      throw new DatabaseException("Error findImages habitat : " . $e->getMessage(), $e->getCode(), $e);
    }
  }

  /**
   * Insert to habitat_image table a new image for animal
   * @param int $animalId id of animal
   * @param int $imageId id of image
   * @return true if image is added
   */
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

  /**
   * Find animals with name for count
   * @param $search string
   * @return int count or null
   */
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

  /**
   * get path for all image of this animal
   * @return array of path or null
   */
  public function getAllImagePath(): array|null
  {
    $imagesPath = [];

    foreach ($this->images as $image) {
      $imagesPath[] = $image->getPath();
    }

    return $imagesPath ?? null;
  }
  /**
   * fetch data for animal_image
   * @param $id id of animal
   * @return array or null
   */
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

  /**
   * fetch animals depending params
   * @param $search string value to search name of habitat
   * @param $order string asc / desc
   * @param $orderBy order by is string column name
   * @return array of Habitat or null
   */
  public function fetchAnimals(string $search, string $order, string $orderBy): array|null
  {
    try {

      $results = null;

      $search .=  '%';

      $orderBy = strtolower($orderBy);

      if ($orderBy == 'nom') {
        $orderBy = 'name';
      }

      $allowedOrderBy = ['id', 'name', 'race',  'habitat'];
      $allowedOrder = ['asc', 'desc'];

      $orderBy = in_array($orderBy, $allowedOrderBy) ? $orderBy : 'id';
      $order = in_array($order, $allowedOrder) ? $order : 'asc';

      $pdo = $this->connect();

      $query = "SELECT animal.id, animal.name, animal.race , habitat.name as habitat
                FROM animal LEFT JOIN habitat ON habitat.id = animal.habitatID
                WHERE animal.race LIKE :search OR animal.name LIKE :search
                OR habitat.name LIKE :search
                ORDER BY $orderBy $order
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

  /**
   * Fetch animals by habitat id
   * @return array ASSOC with animal value or null
   */
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

  /**
   * Fetch animal name by id
   * @param  $id animal id
   * @return array of name or null
   */
  public function fetchAnimalNameById(int $id): array | null
  {
    $pdo = $this->connect();

    $query = "SELECT animal.name FROM $this->table
              WHERE animal.id = :id";

    $stm = $pdo->prepare($query);
    $stm->bindParam(':id', $id, PDO::PARAM_INT);

    if ($stm->execute()) {
      $result = $stm->fetch();
    }

    return $result ?? null;
  }
}
