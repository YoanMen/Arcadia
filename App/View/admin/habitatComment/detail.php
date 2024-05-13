<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title><?= APP_NAME ?>
    | Dashboard - commentaire sur les habitats</title>

  <link rel="icon" href="/assets/images/icons/arcadia-logo.svg" type="image/x-icon">
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
        <h1 class="dashboard__title ">Détails commentaire <?= $data['habitatComment']['habitat'] ?> </h1>
      </div>
      <div class="dashboard__content">
        <ul>
          <li class="details__item">
            <span>de</span>
            <p><?= $data['habitatComment']['email'] ?? 'utilisateur supprimé' ?></p>
          </li>
          <li class="details__item">
            <span>crée le</span>
            <p><?= $data['habitatComment']['updated_at'] ?></p>
          </li>
          <li class="details__item">
            <span>habitat</span>
            <p><?= $data['habitatComment']['habitat'] ?></p>
          </li>
          <li class="details__item">
            <span>commentaire sur l'habitat</span>
            <p><?= $data['habitatComment']['comment'] ?></p>
          </li>
        </ul>
      </div>
    </div>
  </main>


</body>

</html>