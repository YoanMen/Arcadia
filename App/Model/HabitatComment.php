<?php
namespace App\Model;

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
}