<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= APP_NAME ?>
    | Dashboard - utilisateurs</title>
  <link rel="shortcut icon" href="/assets/images/icons/arcadia-logo.svg" type="image/x-icon">
  <link rel="stylesheet" href="/assets/styles/global.css">
  <link rel="stylesheet" href="/assets/styles/dashboard.css">
  <link rel="stylesheet" href="/assets/styles/pagination/pagination.css">
</head>

<body>
  <?php require_once '../App/View/partials/admin/_adminPanel.php' ?>
  <?php require_once '../App/View/partials/admin/_adminTop.php' ?>

  <main class="dashboard">
    <div class="dashboard__container">
      <?php include_once  '../App/View/partials/_success.php' ?>
      <?php include_once   '../App/View/partials/_error.php' ?>
      <div class="dashboard__container__top">
        <h1 class="dashboard__title ">Utilisateurs</h1>
        <a class=" max-width--mobile" href="utilisateurs/add">
          <button class="button max-width--mobile">
            <img height="32px" src="<?= ROOT ?>/assets/images/icons/plus.svg" alt="plus icon" srcset="">
            <span class="hidden--mobile">Ajouter</span>
          </button> </a>
      </div>
      <form id="form" class="container__search" method="GET">
        <input class="max-width" type="search" name="search" placeholder="rechercher par email" value="<?= $data['params']['search']  ?>">
        <input class="button max-width--mobile" type="submit" value="Rechercher">
      </form>
      <div class="dashboard__content">
        <table>
          <thead>
            <tr>
              <th>
                Adresse email
              </th>
              <th class="hidden--mobile">
                Mot de passe
              </th>
              <th>
                <input class="dashboard__params orderBy-js" type="submit" name="orderBy" value="Role" form="form">
                <input class="dashboard__params" type="hidden" id="order" name="order" value='<?= $data['params']['order'] ?>' form="form">
              </th>
              <th>Modifier</th>
              <th>Supprimer</th>
            </tr>
          </thead>
          <tbody>
            <?php if (isset($data['users'])) {
              foreach ($data['users'] as $user) : ?>
                <tr>

                  <td>
                    <?= $user->getEmail() ?>
                  </td>
                  <td class="hidden--mobile"><?= $user->getPassword() ?></td>
                  <td> <?= $user->getRoleTranslated() ?></td>
                  <td>
                    <a href=" utilisateurs/<?= $user->getId() ?>/edit">
                      <img height="32px" src="<?= ROOT ?>/assets/images/icons/pencil.svg" alt="edit icon">
                    </a>
                  </td>
                  <td>
                    <form id="deleteForm" method="POST" action="utilisateurs/<?= $user->getId() ?>/delete">
                      <button class="dashboard__params delete-js" type="submit">
                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
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