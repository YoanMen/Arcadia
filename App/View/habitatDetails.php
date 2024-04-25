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

  <link rel="icon" href="/assets/images/icons/arcadia-logo.svg" type="image/x-icon">
  <link rel="stylesheet" href="/assets/styles/global.css">
  <link rel="stylesheet" href="/assets/styles/section/section.css">
  <link rel="stylesheet" href="/assets/styles/card/interactive-card.css">
  <link rel="stylesheet" href="/assets/styles/nav/mobile-menu.css">
  <link rel="stylesheet" href="/assets/styles/nav/desktop-menu.css">
  <link rel="stylesheet" href="/assets/styles/footer/footer.css">
  <link rel="stylesheet" href="/assets/styles/div/carousel.css">
  <link rel="stylesheet" href="/assets/styles/pagination/pagination.css">

  <link rel="stylesheet" href="/assets/styles/div/breadcrumbs.css">

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

        <?php if ($data['currentPage'] == 1) { ?>
          <h1 class="section__title"><?= $data['habitat']->getName() ?></h1>

          <div class='image'>
            <?php
            if ($data['habitat']->getImage(0) != null) {
              $images = $data['habitat']->getAllImagePath();
              $autoplay = false;

              require_once '../App/View/partials/_carousel.php';
            }
            ?>
          </div>
          <p class="section__background"><?= $data['habitat']->getDescription() ?> </p>
        <?php } ?>

        <ul>
          <?php
          if (isset($data['animals'])) {
            foreach ($data['animals'] as $animal) {
              $haveImage = $animal->getImage(0);
              $textBtn = "En savoir plus";
              $redirection =  ROOT . '/habitats/' . setURLWithName($data['habitat']->getName())  . '/'
                . setURLWithName($animal->getName());

              $pathImg = isset($haveImage) ?  $animal->getImage(0)->getPath() : '';
              $title = $animal->getRace() . " | " . $animal->getName();
              $dataId = "data-animal-id='" . $animal->getId() . "'";

              require '../App/View/partials/_interactiveCard.php' ?>
          <?php     }
          } ?>
        </ul>

        </div>
      <?php
      }
      ?>
    </section>
    <?php require_once '../App/View/partials/_pagination.php' ?>

  </main>
  <?php require_once '../App/View/partials/_footer.php' ?>

  <script src="/assets/scripts/carousel/carousel.js"></script>
  <script src="/assets/scripts/menu.js" type="module"></script>
  <script src="/assets/scripts/clickAnimal.js"></script>
</body>

</html>