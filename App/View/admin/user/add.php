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
        <h1 class="dashboard__title ">Ajouter un utilisateur</h1>
        <button id="add-button" form="add" class="button max-width--mobile">
          <span>Ajouter</span>
        </button>
      </div>
      <div class="dashboard__content">
        <form id='add' method="post">
          <input type="hidden" name="csrf_token" value='<?= $_SESSION['csrf_token'] ?>'>
          <ul>
            <li class="details__item">
              <label for='email'>adresse email</label>
              <input name="email" required minlength="3" maxlength="60" class="details__input" type="text" id="email" value="<?= $data['email'] ?>">
            </li>
            <li class="details__item">
              <label for='password'>mot de passe</label>
              <input name="password" required minlength="8" maxlength="60" class="details__input" type="text" id="password" value="<?= $data['password'] ?>">
            </li>
            <li class="details__item">
              <label for='role'>type de compte</label>
              <select name="role" id="role">
                <option <?= $data['role'] == 'employee' ? 'selected' : '' ?> value="employee">employé</option>
                <option <?= $data['role'] == 'veterinary' ? 'selected' : '' ?> value="veterinary">vétérinaire</option>
              </select>
            </li>
          </ul>
        </form>
      </div>

    </div>

  </main>

  <script src="/assets/scripts/admin/add-element.js"></script>
</body>

</html>