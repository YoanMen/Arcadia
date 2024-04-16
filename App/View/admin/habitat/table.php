<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= APP_NAME ?>
    | Dashboard - habitats</title>
  <link rel="stylesheet" href="<?= ROOT ?>/assets/styles/global.css">
  <link rel="stylesheet" href="<?= ROOT ?>/assets/styles/dashboard.css">
  <link rel="stylesheet" href="<?= ROOT ?>/assets/styles/pagination/pagination.css">
</head>

<body>

  <?php require_once '../App/View/partials/admin/_adminPanel.php' ?>
  <?php require_once '../App/View/partials/admin/_adminTop.php' ?>

  <main class="dashboard">
    <div class="dashboard__container">
      <?php include_once  '../App/View/partials/_success.php' ?>
      <?php include_once   '../App/View/partials/_error.php' ?>
      <div class="dashboard__container__top">
        <h1 class="dashboard__title ">Habitats</h1>
        <a class=" max-width--mobile" href="habitats/add">
          <button class="button max-width--mobile">
            <img height="32px" src="<?= ROOT ?>/assets/images/icons/plus.svg" alt="plus icon" srcset="">
            <span class="hidden--mobile">Ajouter</span>
          </button> </a>
      </div>
      <form id="form" class="container__search" method="GET">
        <input class="max-width" type="search" name="search" placeholder="rechercher par nom, race ou habitat" value="<?= $data['params']['search']  ?>">
        <input class="button max-width--mobile" type="submit" value="Rechercher">
      </form>
      <div class="dashboard__content">
        <table aria-describedby="habitat table">
          <thead>
            <tr>
              <th>
                <input class="dashboard__params orderBy-js" type="submit" name="orderBy" value="Nom" form="form">
                <input class="dashboard__params" type="hidden" id="order" name="order" value='<?= $data['params']['order'] ?>' form="form">
              </th>
              <th>
                Description
              </th>
              <th>Modifier</th>
              <th>Supprimer</th>
            </tr>
          </thead>
          <tbody>
            <?php if (isset($data['habitats'])) {
              foreach ($data['habitats'] as $habitat) : ?>
                <tr>
                  <td>
                    <?= $habitat->getName() ?>
                  </td>
                  <td class="two-line "><?= $habitat->getDescription() ?></td>
                  <td>
                    <a href=" habitats/<?= $habitat->getId() ?>/edit">
                      <img height="32px" src="<?= ROOT ?>/assets/images/icons/pencil.svg" alt="edit icon">
                    </a>
                  </td>
                  <td>
                    <form id="deleteForm" method="POST" action="habitats/<?= $habitat->getId() ?>/delete">
                      <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                      <button class="dashboard__params delete-js" type="submit">
                        <img height=" 32px" src="<?= ROOT ?>/assets/images/icons/delete.svg" alt="delete icon">
                      </button>
                    </form>
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