<?php
namespace App\Model;

class Advice extends Model
{
  protected string $table = 'advice';
  private int $id;
  private string $pseudo;

  private string $advice;

  private bool $approved;


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
   * Get the value of pseudo
   */
  public function getPseudo(): string
  {
    return $this->pseudo;
  }

  /**
   * Set the value of pseudo
   */
  public function sePseudo(string $pseudo): self
  {
    $this->pseudo = $pseudo;

    return $this;
  }

  /**
   * Get the value of advice
   */
  public function getAdvice(): string
  {
    return $this->advice;
  }

  /**
   * Set the value of advice
   */
  public function setAdvice(string $advice): self
  {
    $this->advice = $advice;

    return $this;
  }

  /**
   * Get the value of approved
   */
  public function isApproved(): bool
  {
    return $this->approved;
  }

  /**
   * Set the value of approved
   */
  public function setApproved(bool $approved): self
  {
    $this->approved = $approved;

    return $this;
  }
}