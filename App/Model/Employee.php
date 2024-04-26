<?php

namespace App\Model;

use App\Core\Exception\ValidatorException;

class Employee extends User
{

  /**
   * change statut of advice
   * @param bool $approved to set advice statut
   * @param int $id id of advice
   *
   */
  public function setAdvice(bool $approved, int $id)
  {
    $adviceRepo = new Advice();

    $adviceRepo->update(['approved' => $approved], $id);
  }

  /**
   * function to give food for animal
   */
  public function giveFood(
    int $userId,
    int $animalId,
    string $food,
    string $quantity,
    string $time,
    string $date
  ) {

    if (empty($animalId)) {
      throw new ValidatorException('Aucun animal sélectionné');
    }
    // insert to table
    $foodRepo = new FoodAnimal();

    $foodRepo->insert([
      'userId' => $userId, 'animalId' => $animalId,
      'food' => $food, 'quantity' => $quantity, 'time' => $time,
      'date' => $date
    ]);
  }
}
