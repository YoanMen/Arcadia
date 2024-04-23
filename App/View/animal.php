<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="<?= $_SESSION['csrf_token'] ?>">

  <title>
    <?= APP_NAME ?>
    | <?= ucfirst($data['habitat'])  . ' - ' .  $data['animal']->getName()   ?>
  </title>
  <meta name="description" content="<?= APP_DESC ?>">
  <link rel="shortcut icon" href="/assets/images/icons/arcadia-logo.svg" type="image/x-icon">
  <link rel="stylesheet" href="/assets/styles/global.css">
  <link rel="stylesheet" href="/assets/styles/section/section.css">
  <link rel="stylesheet" href="/assets/styles/card/interactive-card.css">
  <link rel="stylesheet" href="/assets/styles/nav/mobile-menu.css">
  <link rel="stylesheet" href="/assets/styles/nav/desktop-menu.css">
  <link rel="stylesheet" href="/assets/styles/footer/footer.css">
  <link rel="stylesheet" href="/assets/styles/div/carousel.css">
  <link rel="stylesheet" href="/assets/styles/div/breadcrumbs.css">

</head>

<body>
  <?php require_once '../App/View/partials/_menu.php' ?>
  <main>
    <section class="section" name="animal">
      <?php
      if (isset($data['animal']) && isset($data['habitat'])) {
        $elements = [
          ['name' => "Habitats", 'path' =>   '/habitats'],
          ['name' => ucfirst($data['habitat']), 'path' =>  ROOT . '/habitats/' . $data['habitat']],
          ['name' => $data['animal']->getName(), 'path' => '']
        ];
        require_once '../App/View/partials/_breadcrumbs.php' ?>

        <h1 class="section__title"><?= strtoupper($data['animal']->getName())  ?>
          (<?= $data['animal']->getRace() ?>) </h1>
        <div class='image'>
          <?php
          if ($data['animal']->getImage(0) != null) {

            $images = $data['animal']->getAllImagePath();
            $autoplay = false;
            require_once '../App/View/partials/_carousel.php';
          }
          ?>
        </div>
        <?php if (isset($data['report'])) { ?>

          <div class="section__background">
            <p>État : <?= strtoupper($data['report']->getStatut()) ?> </p>

            <?php
            $details = $data['report']->getDetails();
            if (!empty($details)) { ?>
              <h3>Rapport du vétérinaire : </h3>
              <p class="section__text mt"> <?= $details ?> </p>
            <?php  }  ?>
          </div>
      <?php  }
      } ?>
    </section>

  </main>
  <?php require_once '../App/View/partials/_footer.php' ?>

  <script src="/assets/scripts/carousel/carousel.js"></script>
  <script src="/assets/scripts/menu.js" type="module"></script>
</body>

</html>