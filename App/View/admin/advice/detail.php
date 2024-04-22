<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= APP_NAME ?>
    | Dashboard - avis</title>
  <link rel="stylesheet" href="/assets/styles/global.css">
  <link rel="stylesheet" href="/assets/styles/dashboard.css">
  <link rel="stylesheet" href="/assets/styles/dialog.css">
  <link rel="stylesheet" href="/assets/styles/div/details.css">
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
        <h1 class="dashboard__title ">Détails de l'avis</h1>
      </div>
      <div class="dashboard__content">
        <input type="hidden" name="csrf_token" value='<?= $_SESSION['csrf_token'] ?>'>
        <ul>
          <li class="details__item">
            <span>pseudo</span>
            <p><?= $data['advice']->getPseudo() ?></p>
          </li>
          <li class="details__item">
            <span>avis</span>
            <p><?= $data['advice']->getAdvice() ?></p>
          </li>
          <li class="details__item">
            <span>approuvé</span>
            <p><?= $data['advice']->getApproved() ? 'avis approuvé' : 'avis non approuvé' ?></p>
          </li>
        </ul>
      </div>
    </div>
  </main>
</body>

</html>