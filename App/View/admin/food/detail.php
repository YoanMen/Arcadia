<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title><?= APP_NAME ?>
    | Dashboard - nourriture</title>
  <link rel="shortcut icon" href="/assets/images/icons/arcadia-logo.svg" type="image/x-icon">
  <link rel="stylesheet" href="/assets/styles/global.css">
  <link rel="stylesheet" href="/assets/styles/dashboard.css">
  <link rel="stylesheet" href="/assets/styles/dialog.css">
  <link rel="stylesheet" href="/assets/styles/alert.css">
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
        <h1 class="dashboard__title ">Détails alimentation <?= $data['foodAnimal']['name'] ?></h1>
      </div>
      <div class="dashboard__content">
        <input type="hidden" name="csrf_token" value='<?= $_SESSION['csrf_token'] ?>'>
        <ul>
          <li class="details__item">
            <span>de</span>
            <p><?= $data['foodAnimal']['email'] ?></p>
          </li>
          <li class="details__item">
            <span>animal</span>
            <p><?= $data['foodAnimal']['name'] ?></p>
          </li>
          <li class="details__item">
            <span>race</span>
            <p><?= $data['foodAnimal']['race'] ?></p>
          </li>
          <li class="details__item">
            <span>habitat</span>
            <p><?= $data['foodAnimal']['habitat'] ?></p>
          </li>
          <li class="details__item">
            <span>nourriture</span>
            <p><?= $data['foodAnimal']['food'] ?></p>
          </li>
          <li class="details__item">
            <span>quantité</span>
            <p><?= $data['foodAnimal']['quantity'] ?> kg</p>
          </li>
          <li class="details__item">
            <span>nourri le</span>
            <p><?= $data['foodAnimal']['date'] ?></p>
          </li>
          <li class="details__item">
            <span>heure</span>
            <p><?= $data['foodAnimal']['time'] ?></p>
          </li>
        </ul>
      </div>
    </div>
  </main>


</body>

</html>