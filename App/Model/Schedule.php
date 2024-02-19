<?php

namespace App\Model;

use DateTime;

class Schedule extends Model
{
  protected string $table = 'schedule';
  protected string $orderBy = "asc";
  private int $id;
  private string $day;
  private ?string $open;
  private ?string $close;

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
    return ucfirst($this->day);
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
  public function getOpen(): ?string
  {
    return $this->open;
  }

  /**
   * Set the value of open
   */
  public function setOpen(string $open)
  {
    $this->open = $open;
  }

  /**
   * Get the value of close
   */
  public function getClose(): ?string
  {
    return $this->close;
  }

  /**
   * Set the value of close
   */
  public function setClose(string $close)
  {
    $this->close = $close;
  }


  public function getSchedules()
  {
    if (is_null($this->close) && is_null($this->open)) {
      return null;
    } else {
      $openTime = date_create($this->open);
      $closeTime = date_create($this->close);

      return date_format($openTime, "H\hi") . ' - ' . date_format($closeTime, "H\hi");
    }
  }
}