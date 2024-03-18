<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>
    <?= APP_NAME ?>
    | Habitat - <?= (isset($data['habitat'])) ? $data['habitat']->getName() : null  ?>
  </title>
  <meta name="description" content="<?= APP_DESC ?>">
  <meta name="csrf-token" content="<?= $_SESSION['csrf_token'] ?>">


  <link rel="stylesheet" href="<?= ROOT ?>/assets/styles/global.css">
  <link rel="stylesheet" href="<?= ROOT ?>/assets/styles/section/section.css">
  <link rel="stylesheet" href="<?= ROOT ?>/assets/styles/card/interactive-card.css">

  <link rel="stylesheet" href="<?= ROOT ?>/assets/styles/nav/mobile-menu.css">
  <link rel="stylesheet" href="<?= ROOT ?>/assets/styles/nav/desktop-menu.css">
  <link rel="stylesheet" href="<?= ROOT ?>/assets/styles/footer/footer.css">
  <link rel="stylesheet" href="<?= ROOT ?>/assets/styles/div/carousel.css">

  <link rel="stylesheet" href="<?= ROOT ?>/assets/styles/div/breadcrumbs.css">

</head>

<body>
  <?php require_once '../App/View/partials/_menu.php' ?>
  <main>
    <section class="section" name="habitat-details">
      <?php
      if (isset($data['habitat'])) {
        $elements = [
          ['name' => "Habitats", 'path' =>  ROOT . '/habitats'],
          ['name' => $data['habitat']->getName(), 'path' => '']
        ];
        require_once '../App/View/partials/_breadcrumbs.php' ?>

        <h1 class="section__title--secondary"><?= $data['habitat']->getName() ?></h1>

        <div class='image'>
          <?php
          if ($data['habitat']->getImage(0) != null) {
            $images = $data['habitat']->getAllImagePath();
            $autoplay = false;

            require_once '../App/View/partials/_carousel.php';
          }
          ?>
        </div>
        <p class="section__text"><?= $data['habitat']->getDescription() ?> </p>
        <ul>
          <?php
          if (isset($data['animals'])) {
            foreach ($data['animals'] as $animal) {
              $haveImage = $animal->getImage(0);
              $textBtn = "En savoir plus";
              $redirection =  ROOT . '/habitats/' . setURLWithName($data['habitat']->getName())  . '/'
                . setURLWithName($animal->getName());

              $pathImg = isset($haveImage) ?  $animal->getImage(0)->getPath() : '';
              $title = $animal->getRace() . " " . $animal->getName();
              $text = '';

              require '../App/View/partials/_interactiveCard.php' ?>
          <?php     }
          } ?>
        </ul>

        </div>
      <?php
      }
      ?>
    </section>
  </main>
  <?php require_once '../App/View/partials/_footer.php' ?>

  <script src="<?= ROOT ?>/assets/scripts/carousel/carousel.js"></script>
  <script src="<?= ROOT ?>/assets/scripts/menu.js" type="module"></script>
</body>

</html>