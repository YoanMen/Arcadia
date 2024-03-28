<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="<?= $_SESSION['csrf_token'] ?>">

  <title><?= APP_NAME ?>
    | Dashboard</title>
  <link rel="stylesheet" href="<?= ROOT ?>/assets/styles/global.css">
  <link rel="stylesheet" href="<?= ROOT ?>/assets/styles/dashboard.css">
  <link rel="stylesheet" href="<?= ROOT ?>/assets/styles/dialog.css">
  <link rel="stylesheet" href="<?= ROOT ?>/assets/styles/alert.css">
  <link rel="stylesheet" href="<?= ROOT ?>/assets/styles/div/details.css">
</head>

<body>

  <?php require_once '../App/View/partials/admin/_adminPanel.php' ?>
  <?php require_once '../App/View/partials/admin/_adminTop.php' ?>

  <main class="dashboard">
    <div id='dashboard-content' class="dashboard__container">
    </div>
  </main>

  <dialog class='dialog--confirm'>

  </dialog>

  <script type="module" src="<?= ROOT ?>/assets/scripts/admin/navigation.js"></script>
</body>

</html>