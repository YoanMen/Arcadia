<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="<?= $_SESSION['csrf_token'] ?>">
  <title>
    <?= APP_NAME ?>
    | Services
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

    <section class="section">
      <?php
      $elements = [
        ['name' => "Services", 'path' =>  ROOT . '/services'],
      ];
      require_once '../App/View/partials/_breadcrumbs.php' ?>


      <p class="section__text">Le Lorem Ipsum est simplement du faux texte employé dans la
        composition et la mise en page avant impression. Le Lorem Ipsum est le faux texte standard
        de l'imprimerie depuis les années 1500, quand un imprimeur anonyme assembla ensembl à la
        vente de feuilles Letraset </p>

      <div class="section__background">
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
  </main>

  <?php require '../App/View/partials/_footer.php' ?>
  <script src="<?= ROOT ?>/assets/scripts/menu.js"></script>
</body>

</html>