<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= APP_NAME ?>
    | Dashboard - alimentation des animaux</title>
  <link rel="stylesheet" href="<?= ROOT ?>/assets/styles/global.css">
  <link rel="stylesheet" href="<?= ROOT ?>/assets/styles/dashboard.css">
  <link rel="stylesheet" href="<?= ROOT ?>/assets/styles/pagination/pagination.css">
</head>

<body>

  <?php

  use App\Core\Security;

  require_once '../App/View/partials/admin/_adminPanel.php' ?>
  <?php require_once '../App/View/partials/admin/_adminTop.php' ?>

  <main class="dashboard">
    <div class="dashboard__container">
      <?php include_once  '../App/View/partials/_success.php' ?>
      <?php include_once   '../App/View/partials/_error.php' ?>
      <div class="dashboard__container__top">
        <h1 class="dashboard__title ">Alimentation des animaux</h1>
        <?php if (Security::isEmployee()) { ?>
          <a class=" max-width--mobile" href="alimentation-animaux/add">
            <button class="button max-width--mobile">
              <img height="32px" src="<?= ROOT ?>/assets/images/icons/plus.svg" alt="plus icon" srcset="">
              <span class="hidden--mobile">Ajouter</span>
            </button>
          </a>
        <?php } ?>
      </div>
      <form id="form" class="container__search" method="GET">
        <input type="date" name="date" id="date" value="<?= $data['params']['date']  ?>">
        <input class="max-width" type="search" name="search" placeholder="rechercher par nom de l'animal, race, habitat ou email" value="<?= $data['params']['search']  ?>">
        <input class="button  max-width--mobile" type="submit" value="Rechercher">
      </form>
      <div class="dashboard__content">
        <table>
          <thead>
            <tr>
              <th class="hidden--mobile">
                <input class="dashboard__params" type="hidden" id="order" name="order" value='<?= $data['params']['order'] ?>' form="form">
                <input class="dashboard__params orderBy-js" type="submit" name="orderBy" value="De" form="form">
              </th>
              <th>
                <input class="dashboard__params orderBy-js" type="submit" name="orderBy" value="Animal" form="form">
              </th>
              <th class="hidden--mobile">
                <input class="dashboard__params orderBy-js" type="submit" name="orderBy" value="Habitat" form="form">
              </th>
              <th class="hidden--mobile">Nourriture</th>
              <th class="hidden--mobile">Quantité</th>
              <th>
                <input class="dashboard__params orderBy-js" type="submit" name="orderBy" value="Date" form="form">
              </th>
              <th class="hidden--mobile">
                Heure
              </th>
              <th>Détails</th>
            </tr>
          </thead>
          <tbody>
            <?php if (isset($data['foodAnimals'])) {
              foreach ($data['foodAnimals'] as $foodAnimal) : ?>
                <tr>
                  <td class="hidden--mobile">
                    <?= $foodAnimal['email'] ?>
                  </td>
                  <td>
                    <?= $foodAnimal['name'] ?>
                  </td>
                  <td class="hidden--mobile">
                    <?= $foodAnimal['habitat'] ?>
                  </td>
                  <td class="hidden--mobile">
                    <?= $foodAnimal['food'] ?>
                  </td>
                  <td class="hidden--mobile">
                    <?= $foodAnimal['quantity'] ?>g
                  </td>
                  <td>
                    <?= $foodAnimal['date'] ?>
                  </td>
                  <td class="hidden--mobile">
                    <?= $foodAnimal['time'] ?>
                  </td>
                  <td>
                    <a href=" alimentation-animaux/<?= $foodAnimal['id'] ?>/detail">
                      <img height="32px" src="<?= ROOT ?>/assets/images/icons/dots-horizontal.svg" alt="edit icon">
                    </a>
                  </td>
                </tr>
              <?php endforeach ?>
            <?php } else { ?>
              <tr>
                <td>
                  aucun résultat
              </tr>
              </td>
            <?php }  ?>
          </tbody>

        </table>
      </div>
      <?php
      require_once '../App/View/partials/_pagination.php' ?>
    </div>
  </main>


  <script src="<?= ROOT ?>/assets/scripts/admin/main.js"></script>
</body>

</html>