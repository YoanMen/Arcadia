<?php
namespace App\Model;

use Time;


class FoodAnimal extends Model
{
  protected string $table = 'foodAnimal';
  private int $id;
  private int $userID;
  private string $food;
  private float $quantity;
  private string $time;
  private string $date;

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
   * Get the value of quantity
   */
  public function getQuantity(): float
  {
    return $this->quantity;
  }

  /**
   * Set the value of quantity
   */
  public function setQuantity(float $quantity): self
  {
    $this->quantity = $quantity;

    return $this;
  }

  /**
   * Get the value of time
   */
  public function getTime(): string
  {
    return $this->time;
  }

  /**
   * Set the value of time
   */
  public function setTime(string $time): self
  {
    $this->time = $time;

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
}

