<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>
    <?= APP_NAME ?>
    | Services
  </title>
  <meta name="description" content="<?= APP_DESC ?>">
  <meta name="csrf-token" content="<?= $_SESSION['csrf_token'] ?>">


  <link rel="stylesheet" href="<?= ROOT ?>/assets/styles/global.css">
  <link rel="stylesheet" href="<?= ROOT ?>/assets/styles/section/section.css">

  <link rel="stylesheet" href="<?= ROOT ?>/assets/styles/nav/mobile-menu.css">
  <link rel="stylesheet" href="<?= ROOT ?>/assets/styles/nav/desktop-menu.css">
  <link rel="stylesheet" href="<?= ROOT ?>/assets/styles/footer/footer.css">
  <link rel="stylesheet" href="<?= ROOT ?>/assets/styles/div/breadcrumbs.css">
  <link rel="stylesheet" href="<?= ROOT ?>/assets/styles/pagination/pagination.css">

</head>

<body>

  <?php require_once '../App/View/partials/_menu.php' ?>

  <main>
    <section class="section" name="services">
      <?php
      $elements = [
        ['name' => "Services", 'path' => ROOT . '/services'],
      ];
      require_once '../App/View/partials/_breadcrumbs.php' ?>

      <p class="section__text">Explorez les divers services offerts au sein du Zoo Arcadia.
        N'hésitez pas à découvrir l'ensemble de nos prestations.
      </p>

      <div class="section__background">
        <h1 class="section__title--secondary">Tout nos services</h1>

        <ul>
          <?php
          if ($data["services"]) {
            foreach ($data["services"] as $service) { ?>
              <li><a href="<?= ROOT ?>/services/<?= setURLWithName($service->getName()); ?>">
                  <?= $service->getName() ?>
                </a></li>
          <?php }
          }
          ?>
        </ul>
      </div>
    </section>

    <?php
    $baseUrl = ROOT .  '/services';
    require_once '../App/View/partials/_pagination.php' ?>

  </main>

  <?php require_once '../App/View/partials/_footer.php' ?>
  <script src="<?= ROOT ?>/assets/scripts/menu.js" type="module"></script>
</body>

</html>