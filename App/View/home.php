<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="<?= $_SESSION['csrf_token'] ?>">
  <title>
    <?= APP_NAME ?>
    | Accueil
  </title>
  <meta name="description" content="<?= APP_DESC ?>">

  <link rel="icon" href="/assets/images/icons/arcadia-logo.svg" type="image/x-icon">
  <link rel="stylesheet" href="/assets/styles/global.css">
  <link rel="stylesheet" href="/assets/styles/nav/mobile-menu.css">
  <link rel="stylesheet" href="/assets/styles/nav/desktop-menu.css">
  <link rel="stylesheet" href="/assets/styles/hero/hero.css">
  <link rel="stylesheet" href="/assets/styles/div/carousel.css">
  <link rel="stylesheet" href="/assets/styles/section/section.css">
  <link rel="stylesheet" href="/assets/styles/section/schedule.css">
  <link rel="stylesheet" href="/assets/styles/card/btn-card.css">
  <link rel="stylesheet" href="/assets/styles/card/interactive-card.css">
  <link rel="stylesheet" href="/assets/styles/div/testimonial.css">
  <link rel="stylesheet" href="/assets/styles/dialog.css">
  <link rel="stylesheet" href="/assets/styles/footer/footer.css">
</head>

<body>

  <?php require_once '../App/View/partials/_menu.php' ?>

  <div class="hero">
    <?php
    $images = ['hero/panthere.webp', 'hero/pandaroux.webp', 'hero/elephant.jpg'];
    $autoplay = true;
    require_once '../App/View/partials/_carousel.php' ?>
  </div>

  <main>
    <section class="section" id="about">
      <h1 class="section__title">
        Le Zoo Arcadia
      </h1>
      <p class="section__text">
        Fondé en 1960, notre zoo offre une immersion authentique dans le monde naturel avec une collection d'animaux exceptionnels répartis sur différents habitats, y compris la savane africaine vibrante, l'introspection dense de la jungle et les eaux tranquilles des marais.

        Notre engagement pour le bien-être animal est une priorité absolue, avec des équipes expertes qui se déplacent quotidiennement au zoo pour effectuer des contrôles médicaux préventifs et garantir un environnement sain. Nous avons également développé une approche de gestion financière solide, ce qui a permis à notre directeur éminent, José, d'acquérir l'ambition de nous rendre plus que seulement une attraction locale - c'est maintenant la porte vers un voyage sans limite dans le monde animal.

        Nous avons hâte de vous accueillir au zoo Arcadia pour une aventure épique, des rencontres inoubliables et des leçons d'écologie qui vont changer votre vie. Découvrez nos parcours animaliers avec nous aujourd'hui !
      </p>
      <img class="image" src="/assets/images/1548643.webp" alt="" loading="lazy">
      <?php
      $title = 'Découvrez nos Services';
      $path = '/services';
      $textBtn = 'Voir les services';
      require '../App/View/partials/_buttonCard.php' ?>
    </section>

    <section class="section" id="habitat">
      <h2 class="section__title">
        Les Habitats
      </h2>
      <p class="section__text">
        Découvrez les habitats uniques qui abritent nos résidents exceptionnels.

        Plongez dans un monde fascinant où chaque espace est soigneusement conçu pour recréer l'environnement naturel de nos animaux. Explorez des habitats divers, de la luxuriante jungle tropicale aux vastes plaines africaines.

        À travers nos installations, nous nous engageons à offrir à chaque habitant un lieu sûr et confortable, où ils peuvent
        s'épanouir et exprimer pleinement leur nature. Rejoignez-nous pour une immersion captivante dans la vie de nos pensionnaires, tout en soutenant notre engagement envers le bien-être animal et la conservation de la biodiversité.
      </p>
      <ul>
        <?php
        if (isset($data['habitats'])) {
          // create interactive cards to show habitats
          foreach ($data['habitats'] as $habitat) {
            $haveImage = $habitat->getImage(0);
            $textBtn = "Découvrir cette habitat";
            $redirection =   'habitats/' . setURLWithName($habitat->getName());
            $pathImg = isset($haveImage) ?  $habitat->getImage(0)->getPath() : '';
            $title = $habitat->getName();
            require '../App/View/partials/_interactiveCard.php' ?>
        <?php     }
        } ?>
      </ul>

      <?php
      $title = "D'autres habitats à découvrir";
      $path =  '/habitats';
      $textBtn = 'Voir tous les habitats';
      require '../App/View/partials/_buttonCard.php' ?>

      <img class="image image--bottom" src="/assets/images/88484611.webp" alt="a panda eating bamboo" loading="lazy">
    </section>

    <section class="section" id="testimonial">
      <h2 class="section__title">
        Les Avis
      </h2>
      <div class="testimonial__container">
        <div class="testimonial__hidden">
          <button aria-label="previous advice" id="left-btn" disabled class="button button--cube" type="button">
            <svg fill='black' xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
              <path d="M15.41,16.58L10.83,12L15.41,7.41L14,6L8,12L14,18L15.41,16.58Z" />
            </svg>
          </button>
        </div>
        <div id="testimonial-card" class="testimonial__card">
          <p id="testimonial-text" class="testimonial__text"></p>
          <hr>
          <p id="testimonial-pseudo" class="testimonial__pseudo"></p>
        </div>
        <div class="testimonial__hidden ">
          <button aria-label="next advice" disabled id="right-btn" class="button  button--cube" type="button">
            <svg fill='black' xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
              <path d="M15.41,16.58L10.83,12L15.41,7.41L14,6L8,12L14,18L15.41,16.58Z" />
            </svg>
          </button>
        </div>
      </div>

      <?php
      $title = "Laissez-nous votre avis";
      $path = null;
      $textBtn = 'Laisser un avis';
      $idName = 'add-testimonial';
      require '../App/View/partials/_buttonCard.php' ?>
    </section>


    <section class="section" id="schedule">
      <h2 class="section__title">
        Horaires d'ouverture </h2>
      <?php require_once '../App/View/partials/_schedule.php' ?>
    </section>
  </main>

  <dialog class='dialog'>
    <div class="dialog__top">
      <h3 class="dialog__title">Ajouter un Avis</h3>
      <div class="dialog__top__buttons">
        <button class='dialog__close button button--cube'>
          <svg fill='white' xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
            <path d="M19,6.41L17.59,5L12,10.59L6.41,5L5,6.41L10.59,12L5,17.59L6.41,19L12,13.41L17.59,19L19,17.59L13.41,12L19,6.41Z" />
          </svg>
        </button>
      </div>
    </div>
    <div id="dialog-content" class="dialog__content">
      <form method="dialog" class="dialog__form">
        <label for="pseudo">Pseudo </label>
        <input required type="text" name="pseudo" id="pseudo" minlength="3" maxlength="20">
        <label for="message">Message</label>
        <textarea required name="message" id="message" cols="30" rows="10" minlength="3" maxlength="300"></textarea>
        <button disabled id="send-button" class="button max-width">Envoyer mon avis</button>
      </form>
    </div>
  </dialog>

  <?php require_once '../App/View/partials/_footer.php' ?>
  <script src="/assets/scripts/menu.js" type="module"></script>
  <script src="/assets/scripts/carousel/carousel.js"></script>
  <script src="/assets/scripts/testimonial/testimonial-slider.js"> </script>
  <script src="/assets/scripts/testimonial/dialogTestimonial.js"></script>

</body>

</html>