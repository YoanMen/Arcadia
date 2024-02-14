<?php
namespace App\Model;

class Schedule extends Model
{
  protected string $table = 'schedule';
  private int $id;
  private string $day;
  private string $open;
  private string $close;

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
   * Get the value of day
   */
  public function getDay(): string
  {
    return $this->day;
  }

  /**
   * Set the value of day
   */
  public function setDay(string $day): self
  {
    $this->day = $day;

    return $this;
  }

  /**
   * Get the value of open
   */
  public function getOpen(): string
  {
    return $this->open;
  }

  /**
   * Set the value of open
   */
  public function setOpen(string $open): self
  {
    $this->open = $open;

    return $this;
  }

  /**
   * Get the value of close
   */
  public function getClose(): string
  {
    return $this->close;
  }

  /**
   * Set the value of close
   */
  public function setClose(string $close): self
  {
    $this->close = $close;

    return $this;
  }
}