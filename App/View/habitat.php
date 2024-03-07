<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>
    <?= APP_NAME ?>
    | Habitats
  </title>
  <meta name="description" content="<?= APP_DESC ?>">


  <link rel="stylesheet" href="<?= ROOT ?>/assets/styles/global.css">
  <link rel="stylesheet" href="<?= ROOT ?>/assets/styles/section/section.css">

  <link rel="stylesheet" href="<?= ROOT ?>/assets/styles/nav/mobile-menu.css">
  <link rel="stylesheet" href="<?= ROOT ?>/assets/styles/nav/desktop-menu.css">
  <link rel="stylesheet" href="<?= ROOT ?>/assets/styles/footer/footer.css">
  <link rel="stylesheet" href="<?= ROOT ?>/assets/styles/div/breadcrumbs.css">
  <link rel="stylesheet" href="<?= ROOT ?>/assets/styles/card/interactive-card.css">

  <link rel="stylesheet" href="<?= ROOT ?>/assets/styles/pagination/pagination.css">


</head>

<body>

  <?php require_once '../App/View/partials/_menu.php' ?>

  <main>
    <section class="section" name="habitats">
      <?php
      $elements = [
        ['name' => "Habitats", 'path' => ROOT . '/habitats'],
      ];
      require_once '../App/View/partials/_breadcrumbs.php' ?>

      <p class="section__text">
        Explorez les différents habitats du Zoo Arcadia.
      </p>

      <ul>
        <?php
        if (isset($data['habitats'])) {
          foreach ($data['habitats'] as $habitat) {
            $haveImage = $habitat->getImage(0);
            $textBtn = "Découvrir cette habitat";
            $redirection =  'habitats/' . setURLWithName($habitat->getName());
            $pathImg = isset($haveImage) ?  $habitat->getImage(0)->getPath() : '';
            $title = $habitat->getName();
            $text = $habitat->getDescription();

            require '../App/View/partials/_interactiveCard.php' ?>
        <?php     }
        } ?>
      </ul>
    </section>

    <?php
    $baseUrl = ROOT .  '/habitats';
    require '../App/View/partials/_pagination.php' ?>

  </main>

  <?php require '../App/View/partials/_footer.php' ?>
  <script src="<?= ROOT ?>/assets/scripts/menu.js"></script>

</body>

</html>