<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>
    <?= APP_NAME ?>
    | Contact
  </title>
  <meta name="description" content="<?= APP_DESC ?>">
  <meta name="csrf-token" content="<?= $_SESSION['csrf_token'] ?>">

  <link rel="stylesheet" href="./assets/styles/global.css">
  <link rel="stylesheet" href="./assets/styles/section/section.css">

  <link rel="stylesheet" href="./assets/styles/nav/mobile-menu.css">
  <link rel="stylesheet" href="./assets/styles/nav/desktop-menu.css">
  <link rel="stylesheet" href="./assets/styles/footer/footer.css">
  <link rel="stylesheet" href="./assets/styles/div/contact.css">

  <link rel="stylesheet" href="./assets/styles/pagination/pagination.css">

</head>

<body>

  <?php require_once '../App/View/partials/_menu.php' ?>

  <main>
    <section class="section contact">
      <div class="section__background contact__container">
        <form class="contact__form" method="post">
          <input type="hidden" name="csrf_token" value='<?= $_SESSION['csrf_token'] ?>'>
          <h2>Nous contacter</h2>
          <?php include_once  '../App/View/partials/_success.php' ?>
          <?php include_once   '../App/View/partials/_error.php' ?>
          <div>
            <label for="email">Votre adresse email</label>
            <input class="max-width" type="email" id="email" name="email" required>
          </div>
          <div>
            <label for="title">Titre</label>
            <input class="max-width" minlength="2" maxlength="40" type="text" id="title" name="title" required>
          </div>
          <div>
            <label for="message">Message</label>
            <textarea class="max-width" id="message" name="message" rows="5" cols="30" required></textarea>
          </div>
          <div>
            <input class="button max-width contact__form__button" type="submit" value="Envoyer">
          </div>
        </form>
      </div>
    </section>
  </main>

  <?php require_once '../App/View/partials/_footer.php' ?>
  <script src="./assets/scripts/menu.js" type="module"></script>
</body>

</html>