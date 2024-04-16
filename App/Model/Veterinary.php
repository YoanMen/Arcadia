<?php

namespace App\Model;

use App\Core\Exception\ValidatorException;

class Veterinary extends User
{
  private HabitatComment $habitatsCommentRepo;
  private ReportAnimal $reportAnimal;

  public function __construct()
  {
    $this->habitatsCommentRepo = new HabitatComment();
    $this->reportAnimal = new ReportAnimal();
  }

  public function commentHabitat(int $userId, int $habitat, string $comment)
  {
    $commentHabitat = $this->habitatsCommentRepo->find(['habitatId' => $habitat]);

    if ($commentHabitat) {
      $this->habitatsCommentRepo->updateComment($habitat, $userId, $comment);
    } else {
      $this->habitatsCommentRepo->insert(['comment' => $comment, 'userId' => $userId, 'habitatId' => $habitat]);
    }
  }

  public function reportAnimal(
    int $userId,
    int $animalId,
    string $food,
    string $quantity,
    string $date,
    ?string $details,
    string $statut
  ) {

    if (empty($animalId)) {
      throw new ValidatorException('Aucun animal sélectionné');
    }

    $this->reportAnimal->insert([
      'userId' => $userId, 'animalId' => $animalId,
      'food' => $food, 'weight' => $quantity,
      'date' => $date, 'details' => $details,
      'statut' => $statut
    ]);
  }
}