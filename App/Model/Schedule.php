<?php

namespace App\Model;

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
    if ($this->open) {
      return   substr($this->open, 0, -3);
    }

    return null;
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
    if ($this->close) {
      return   substr($this->close, 0, -3);
    }

    return null;
  }

  /**
   * Set the value of close
   */
  public function setClose(string $close)
  {
    $this->close = $close;
  }

  /**
   * function to get schedules
   * @return null | string format 'H\hi' depending if open and close time is null or not
   */
  public function getSchedules(): null | string
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
