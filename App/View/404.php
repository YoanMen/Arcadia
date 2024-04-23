<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="<?= $_SESSION['csrf_token'] ?>">
  <title>
    <?= APP_NAME ?>
    | Erreur
  </title>
  <meta name="description" content="<?= APP_DESC ?>">


  <link rel="stylesheet" href="/assets/styles/global.css">
  <link rel="stylesheet" href="/assets/styles/section/section.css">
  <link rel="stylesheet" href="/assets/styles/card/btn-card.css">
  <link rel="stylesheet" href="/assets/styles/nav/mobile-menu.css">
  <link rel="stylesheet" href="/assets/styles/nav/desktop-menu.css">
  <link rel="stylesheet" href="/assets/styles/footer/footer.css">
</head>

<body>

  <?php require_once '../App/View/partials/_menu.php' ?>



  <main class="section__center">
    <?php
    $title = "Cette page n'existe pas";
    $path =  '/';
    $textBtn = 'retourner à l\'accueil';
    require_once '../App/View/partials/_buttonCard.php' ?>
  </main>

  <?php require_once '../App/View/partials/_footer.php' ?>
  <script src="/assets/scripts/menu.js" type="module"></script>

</body>

</html>