<?php
namespace App\Model;

use DateTime;

class ReportAnimal extends Model
{
  protected string $table = 'reportAnimal';
  private int $id;
  private int $userID;
  private string $food;
  private float $weight;
  private string $date;
  private string $details;

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
   * Get the value of food
   */
  public function getFood(): string
  {
    return $this->food;
  }

  /**
   * Set the value of food
   */
  public function setFood(string $food): self
  {
    $this->food = $food;

    return $this;
  }

  /**
   * Get the value of weight
   */
  public function getWeight(): float
  {
    return $this->weight;
  }

  /**
   * Set the value of weight
   */
  public function setWeight(float $weight): self
  {
    $this->weight = $weight;

    return $this;
  }

  /**
   * Get the value of date
   */
  public function getDate(): string
  {
    return $this->date;
  }

  /**
   * Set the value of date
   */
  public function setDate(string $date): self
  {
    $this->date = $date;

    return $this;
  }

  /**
   * Get the value of details
   */
  public function getDetails(): string
  {
    return $this->details;
  }

  /**
   * Set the value of details
   */
  public function setDetails(string $details): self
  {
    $this->details = $details;

    return $this;
  }
}