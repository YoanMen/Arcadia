<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title><?= APP_NAME ?>
    | Dashboard - commentaire sur les habitats</title>
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
        <h1 class="dashboard__title ">Ajouter un commentaire pour l'habitat </h1>
        <button id="add-button" form="add" class="button max-width--mobile">
          <span>Ajouter</span>
        </button>
      </div>
      <div class="dashboard__content">
        <form enctype="multipart/form-data" id='add' method="post">
          <input type="hidden" name="csrf_token" value='<?= $_SESSION['csrf_token'] ?>'>
          <ul>
            <li class="details__item">
              <label for='habitat'>habitat</label>
              <select name="habitat" id="habitat">
                <?php if (isset($data['habitats'])) {
                  foreach ($data['habitats'] as $habitat) { ?>
                    <option <?= $data['habitat'] ==  $habitat['id'] ? 'selected' : '' ?> value="<?= $habitat['id'] ?>"><?= $habitat['name']  ?></option>
                <?php   }
                } ?>
              </select>
            </li>
            <li class="details__item">
              <label for='comment'>commentaire</label>
              <textarea required minlength="3" class="max-width" name="comment" id="details" cols="30" rows="10"><?= $data['comment'] ?></textarea>
            </li>
          </ul>
        </form>
      </div>
    </div>
  </main>

  <script src="/assets/scripts/admin/add-element.js"></script>
</body>

</html>