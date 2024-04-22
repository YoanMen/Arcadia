<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= APP_NAME ?>
    | Dashboard - avis</title>
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
      <div class="dashboard__conta  iner__top">
        <h1 class="dashboard__title ">Avis des visiteurs</h1>
      </div>
      <form id="form" class="container__search" method="GET">
        <input class="max-width" type="search" name="search" placeholder="rechercher par pseudo" value="<?= $data['params']['search']  ?>">
        <input class="button max-width--mobile" type="submit" value="Rechercher">
      </form>
      <div class="dashboard__content">
        <table aria-describedby="advice table">
          <thead>
            <tr>
              <th>
                <input class="dashboard__params orderBy-js" type="submit" name="orderBy" value="Pseudo" form="form">
                <input class="dashboard__params" type="hidden" id="order" name="order" value='<?= $data['params']['order'] ?>' form="form">
              </th>
              <th>
                Commentaire
              </th>
              <th>
                <input class="dashboard__params orderBy-js" type="submit" name="orderBy" value="Approuvé" form="form">
              </th>
              <th>
                Détails
              </th>
            </tr>
          </thead>
          <tbody>
            <input type="hidden" id="csrf_token" name="csrf_token" value='<?= $_SESSION['csrf_token'] ?>'>
            <?php if (isset($data['advices'])) {
              foreach ($data['advices'] as $advice) : ?>
                <tr class="advice-js">
                  <td>
                    <?= $advice->getPseudo() ?>
                  </td>
                  <td class="two-line"><?= $advice->getAdvice() ?></td>
                  <td>
                    <label class="switch">
                      <input data-advice-id="<?= $advice->getId()  ?>" type="checkbox" id="approved" <?= $advice->getApproved() ? 'checked' : '' ?>>
                      <span class="slider round"></span>
                    </label>
                  </td>
                  <td>
                    <a href="avis/<?= $advice->getId() ?>/detail">
                      <img height="32px" src="/assets/images/icons/dots-horizontal.svg" alt="details icon">
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

  <script src="/assets/scripts/admin/advices.js"></script>
  <script src="/assets/scripts/admin/main.js"></script>
</body>

</html>