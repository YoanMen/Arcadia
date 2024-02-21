<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>
    <?= APP_NAME ?>
    | <?= (isset($data['service'])) ? $data['service']->getName() : null  ?>
  </title>
  <meta name="description" content="<?= APP_DESC ?>">


  <link rel="stylesheet" href="<?= ROOT ?>/assets/styles/global.css">
  <link rel="stylesheet" href="<?= ROOT ?>/assets/styles/section/section.css">
  <link rel="stylesheet" href="<?= ROOT ?>/assets/styles/card/interactive-card.css">

  <link rel="stylesheet" href="<?= ROOT ?>/assets/styles/nav/mobile-menu.css">
  <link rel="stylesheet" href="<?= ROOT ?>/assets/styles/nav/desktop-menu.css">
  <link rel="stylesheet" href="<?= ROOT ?>/assets/styles/footer/footer.css">
  <link rel="stylesheet" href="<?= ROOT ?>/assets/styles/div/breadcrumbs.css">

</head>

<body>
  <?php require_once '../App/View/partials/_menu.php' ?>
  <main>
    <section class="section" name="service-details">
      <?php
      if (isset($data['service'])) {
        $elements = [
          ['name' => "Services", 'path' =>  ROOT . '/services'],
          ['name' => $data['service']->getName(), 'path' => '']
        ];
        require_once '../App/View/partials/_breadcrumbs.php' ?>
        <div class="section__background">
          <h1 class="section__title--secondary"><?= $data['service']->getName() ?></h1>
          <p class="section__text"><?= $data['service']->getDescription() ?> </p>
        </div>
      <?php
      }
      ?>
    </section>
  </main>
  <?php require '../App/View/partials/_footer.php' ?>

  <script src="<?= ROOT ?>/assets/scripts/menu.js"></script>
</body>

</html>