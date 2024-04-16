<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= APP_NAME ?>
    | Dashboard - habitats</title>
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
        <h1 class="dashboard__title ">Ajouter un habitat</h1>
        <button form="add" class="button max-width--mobile">
          <span>Ajouter</span>
        </button>
      </div>
      <div class="dashboard__content">
        <form enctype="multipart/form-data" id='add' method="post">
          <input type="hidden" name="csrf_token" value='<?= $_SESSION['csrf_token'] ?>'>
          <ul>
            <li class="details__item">
              <label for='name'>nom</label>
              <input name="name" required minlength="3" maxlength="60" class="details__input" type="text" id="name" value="<?= $data['name'] ?>">
            </li>
            <li class="details__item">
              <label for='description'>description</label>
              <textarea class="max-width" required minlength="10" name="description" id="description" cols="30" rows="10"><?= $data['description'] ?></textarea>
            </li>
            <li class="details__item">
              <label for="habitatImage">image</label>
              <input required max-file-size="<?= MAX_FILE_SIZE ?>" id="image-input" class="details__input" type="file" id="file" name="file" accept="image/png, image/jpeg, image/webp">
            </li>
          </ul>
        </form>
      </div>
    </div>
  </main>
</body>

</html>