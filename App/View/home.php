<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
      <?= APP_NAME ?>
      | Accueil
    </title>
    <meta name="description" content="<?= APP_DESC ?>">


    <link rel="stylesheet" href="<?= ROOT ?>/assets/styles/global.css">
    <link rel="stylesheet" href="<?= ROOT ?>/assets/styles/nav/mobile-menu.css">
    <link rel="stylesheet" href="<?= ROOT ?>/assets/styles/nav/desktop-menu.css">
  </head>
  <body>

    <?php require_once '../App/View/partials/_menu.php' ?>

    <script src="<?= ROOT ?>/assets/js/menu.js"></script>

  </body>
</html>

