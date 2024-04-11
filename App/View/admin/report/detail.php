<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title><?= APP_NAME ?>
    | Dashboard - nourriture</title>
  <link rel="stylesheet" href="<?= ROOT ?>/assets/styles/global.css">
  <link rel="stylesheet" href="<?= ROOT ?>/assets/styles/dashboard.css">
  <link rel="stylesheet" href="<?= ROOT ?>/assets/styles/dialog.css">
  <link rel="stylesheet" href="<?= ROOT ?>/assets/styles/alert.css">
  <link rel="stylesheet" href="<?= ROOT ?>/assets/styles/div/details.css">
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
        <h1 class="dashboard__title ">Détails rapport <?= $data['reportAnimal']['name'] ?> </h1>
      </div>
      <div class="dashboard__content">
        <input type="hidden" name="csrf_token" value='<?= $_SESSION['csrf_token'] ?>'>
        <ul>
          <li class="details__item">
            <span>de</span>
            <p><?= $data['reportAnimal']['email'] ?></p>
          </li>
          <li class="details__item">
            <span>rapport fait le</span>
            <p><?= $data['reportAnimal']['date'] ?></p>
          </li>
          <li class="details__item">
            <span>état de l'animal</span>
            <p><?= $data['reportAnimal']['statut'] ?></p>
          </li>
          <li class="details__item">
            <span>animal</span>
            <p><?= $data['reportAnimal']['name'] ?></p>
          </li>
          <li class="details__item">
            <span>race</span>
            <p><?= $data['reportAnimal']['race'] ?></p>
          </li>
          <li class="details__item">
            <span>habitat</span>
            <p><?= $data['reportAnimal']['habitat'] ?></p>
          </li>
          <li class="details__item">
            <span>nourriture recommandé</span>
            <p><?= $data['reportAnimal']['food'] ?></p>
          </li>
          <li class="details__item">
            <span>poids de la nourriture</span>
            <p><?= $data['reportAnimal']['weight'] ?> grammes</p>
          </li>
          <li class="details__item">
            <span>commentaire </span>
            <p><?= $data['reportAnimal']['details'] ?></p>
          </li>
        </ul>
      </div>
    </div>
  </main>


</body>

</html>