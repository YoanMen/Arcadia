<?php

namespace App\Model;

use App\Core\Exception\ValidatorException;

class Employee extends User
{

  public function setAdvice(bool $approved, int $id)
  {
    $adviceRepo = new Advice();

    $adviceRepo->update(['approved' => $approved], $id);
  }

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