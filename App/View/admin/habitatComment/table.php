<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= APP_NAME ?>
    | Dashboard - commentaire sur les habitats </title>

  <link rel="icon" href="/assets/images/icons/arcadia-logo.svg" type="image/x-icon">
  <link rel="stylesheet" href="/assets/styles/global.css">
  <link rel="stylesheet" href="/assets/styles/dashboard.css">
  <link rel="stylesheet" href="/assets/styles/pagination/pagination.css">
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
        <h1 class="dashboard__title ">Commentaire sur les habitats</h1>
        <?php if (Security::isVeterinary()) { ?>
          <a class=" max-width--mobile" href="commentaire-habitats/add">
            <button class="button max-width--mobile">
              <img height="32px" src="<?= ROOT ?>/assets/images/icons/plus.svg" alt="plus icon" srcset="">
              <span class="hidden--mobile">Ajouter</span>
            </button>
          </a>
        <?php  } ?>
      </div>
      <form id="form" class="container__search" method="GET">
        <input class="max-width" type="search" name="search" placeholder="rechercher par nom d'habitat" value="<?= $data['params']['search']  ?>">
        <input class="button  max-width--mobile" type="submit" value="Rechercher">
      </form>
      <div class="dashboard__content">
        <table aria-describedby="habitat comment table">
          <thead>
            <tr>
              <th class="hidden--mobile">
                <input class="dashboard__params" type="hidden" id="order" name="order" value='<?= $data['params']['order'] ?>' form="form">
                <input class="dashboard__params orderBy-js" type="submit" name="orderBy" value="De" form="form">
              </th>
              <th>
                <input class="dashboard__params orderBy-js" type="submit" name="orderBy" value="Habitat" form="form">
              </th>
              <th class="hidden--mobile">Commentaire</th>
              <th>Détails</th>
            </tr>
          </thead>
          <tbody>
            <?php if (isset($data['habitatComments'])) {
              foreach ($data['habitatComments'] as $habitatComment) : ?>
                <tr>
                  <td class="hidden--mobile">
                    <?= $habitatComment['email'] ?? 'utilisateur supprimé' ?>
                  </td>
                  <td>
                    <?= $habitatComment['habitat'] ?>
                  </td>
                  <td class="hidden--mobile two-line">
                    <?= $habitatComment['comment'] ?>
                  </td>
                  <td>
                    <a href="commentaire-habitats/<?= $habitatComment['id'] ?>/detail">
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


  <script src="/assets/scripts/admin/main.js"></script>
</body>

</html>