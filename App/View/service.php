<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>
    <?= APP_NAME ?>
    | Services
  </title>
  <meta name="description" content="<?= APP_DESC ?>">
  <meta name="csrf-token" content="<?= $_SESSION['csrf_token'] ?>">

  <link rel="icon" href="/assets/images/icons/arcadia-logo.svg" type="image/x-icon">
  <link rel="stylesheet" href="/assets/styles/global.css">
  <link rel="stylesheet" href="/assets/styles/section/section.css">
  <link rel="stylesheet" href="/assets/styles/nav/mobile-menu.css">
  <link rel="stylesheet" href="/assets/styles/nav/desktop-menu.css">
  <link rel="stylesheet" href="/assets/styles/footer/footer.css">
  <link rel="stylesheet" href="/assets/styles/div/breadcrumbs.css">
  <link rel="stylesheet" href="/assets/styles/pagination/pagination.css">

</head>

<body>

  <?php require_once '../App/View/partials/_menu.php' ?>

  <main>
    <section class="section" name="services">
      <?php
      $elements = [
        ['name' => "Services", 'path' =>  '/services'],
      ];
      require_once '../App/View/partials/_breadcrumbs.php' ?>


      <?php if ($data['currentPage'] == 1) { ?>
        <p class="section__text">Explorez les divers services offerts au sein du Zoo Arcadia. </p>
      <?php } ?>


      <div class="section__background">
        <h1 class="section__title--secondary">Tous nos services</h1>

        <ul>
          <?php
          if ($data["services"]) {
            foreach ($data["services"] as $service) { ?>
              <li>
                <a aria-label="link for service <?= $service->getName() ?>" href="/services/<?= setURLWithName($service->getName()); ?>">
                  <?= $service->getName() ?>
                </a>
              </li>
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
  <script src="/assets/scripts/menu.js" type="module"></script>
</body>

</html>