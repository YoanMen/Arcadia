<?php

namespace App\Core;

class CouchDB
{

  public function deleteAnimalDocument(string $animalId): bool
  {
    $document = $this->getDocumentByID($animalId);

    if ($document) {

      $curl = curl_init(COUCHDB_URL . '/' . $animalId . '?rev=' . $document['_rev']);

      curl_setopt_array($curl, [
        CURLOPT_CUSTOMREQUEST => 'DELETE',
        CURLOPT_RETURNTRANSFER => true,

      ]);

      curl_exec($curl);
      curl_close($curl);

      return true;
    }

    return false;
  }

  public function createAnimalDocument(string $animalId): bool
  {
    $curl = curl_init(COUCHDB_URL);

    curl_setopt_array($curl, [
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_POSTFIELDS => json_encode([
        '_id' => $animalId,
        'click' => 1
      ])
    ]);

    $response = curl_exec($curl);
    curl_close($curl);
    return $response;
  }

  public function getFamousAnimals(): array | null
  {
    $curl = curl_init(COUCHDB_URL . '/_find/click_index');

    curl_setopt_array($curl, [
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_POSTFIELDS => json_encode([
        'selector' => ['click' => ['$gt' => 0]],
        'fields' => ["_id", "click"],
        'sort' => [['click' => 'desc']],
        'limit' => 5,
      ])
    ]);

    $response = curl_exec($curl);
    curl_close($curl);
    $data = json_decode($response, true);

    return empty($data['docs']) ? null : $data['docs'];
  }
  public function getDocumentByID(string $animalId): null | array
  {
    $curl = curl_init(COUCHDB_URL . '/' . $animalId);
    curl_setopt_array($curl, [
      CURLOPT_CUSTOMREQUEST => 'GET',
      CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
      CURLOPT_RETURNTRANSFER => true,
    ]);

    $response = curl_exec($curl);
    curl_close($curl);

    $data = json_decode($response, true);


    return isset($data['error']) ? null :  [
      'click' => $data['click'],
      '_rev' => $data['_rev']
    ];
  }

  public function addClick(string $animalId)
  {
    $document = $this->getDocumentByID($animalId);

    if ($document) {

      $click = $document['click'];
      $curl = curl_init(COUCHDB_URL . '/' . $animalId);

      curl_setopt_array($curl, [
        CURLOPT_CUSTOMREQUEST => 'PUT',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
        CURLOPT_POSTFIELDS => json_encode([
          'click' => $click + 1,
          '_rev' => $document['_rev']
        ])
      ]);

      $response = curl_exec($curl);


      curl_close($curl);
    } else {
      $this->createAnimalDocument($animalId);
    }
  }
}
