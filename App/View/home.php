<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>
    <?= APP_NAME ?>
    | Accueil
  </title>
  <meta name="description" content="<?= APP_DESC ?>">


  <link rel="stylesheet" href="<?= ROOT ?>/assets/styles/global.css">
  <link rel="stylesheet" href="<?= ROOT ?>/assets/styles/hero/hero.css">
  <link rel="stylesheet" href="<?= ROOT ?>/assets/styles/section/section.css">
  <link rel="stylesheet" href="<?= ROOT ?>/assets/styles/section/schedule.css">

  <link rel="stylesheet" href="<?= ROOT ?>/assets/styles/card/btn-card.css">
  <link rel="stylesheet" href="<?= ROOT ?>/assets/styles/card/interactive-card.css">
  <link rel="stylesheet" href="<?= ROOT ?>/assets/styles/div/testimonial.css">
  <link rel="stylesheet" href="<?= ROOT ?>/assets/styles/nav/mobile-menu.css">
  <link rel="stylesheet" href="<?= ROOT ?>/assets/styles/nav/desktop-menu.css">
</head>

<body>

  <?php require_once '../App/View/partials/_menu.php' ?>


  <div class="hero">
    <img src="<?= ROOT ?>/assets/images/hero.webp" alt="" srcset="">
  </div>

  <main>
    <section class="section" id="about">
      <h1 class="section__title">
        Le Zoo Arcadia
      </h1>
      <p class="section__text">
        Explorez la magie d'Arcadia, un zoo enchanteur niché près de la
        mystique forêt de Brocéliande en Bretagne depuis 1960. Notre parc propose une expérience
        immersive où la diversité animale est harmonieusement répartie dans des habitats uniques
        tels que la savane, la jungle et le marais.
        <br>
        <br>
        La santé et le bien-être de nos précieux habitants sont au cœur de nos préoccupations.
        <br>
        <br>
        Notre engagement envers le bien-être animal se reflète non seulement dans les soins médicaux réguliers,
        mais aussi dans la gestion précise de leur alimentation. Chaque repas est minutieusement calculé, avec
        le grammage optimal spécifié dans les rapports détaillés des vétérinaires.
        <br>
        <br>
        Arcadia est bien plus qu'un zoo ; c'est un refuge écologique. Nous sommes fiers de notre indépendance
        énergétique, témoignant de notre engagement envers l'écologie.
      </p>
      <img class="section__img image" src="<?= ROOT ?>/assets/images/1548643.webp" alt="">
      <?php
      $title = 'Découvrez nos Services';
      $path = '/service';
      $textBtn = 'Voir les services';
      require '../App/View/partials/_buttonCard.php' ?>

    </section>


    <section class="section" id="habitat">
      <h2 class="section__title">
        Les Habitats
      </h2>
      <p class="section__text">
        Découvrez les habitats uniques qui abritent nos résidents exceptionnels.
        Plongez dans un monde fascinant où chaque espace est soigneusement conçu pour
        recréer l'environnement naturel de nos animaux. Explorez des habitats divers, de
        la luxuriante jungle tropicale aux vastes plaines africaines. À travers nos installations,
        nous nous engageons à offrir à chaque habitant un lieu sûr et confortable, où ils peuvent
        s'épanouir et exprimer pleinement leur nature. Rejoignez-nous pour une immersion captivante
        dans la vie de nos pensionnaires, tout en soutenant notre engagement envers le bien-être animal
        et la conservation de la biodiversité.
      </p>


      <ul>
        <?php
        if (isset($data['habitats'])) {
          foreach ($data['habitats'] as $habitat) {
            $haveImage = $habitat->getImage(0);
            $textBtn = "Découvrir cette habitat";
            $redirection = '/habitats';
            $pathImg = isset($haveImage) ?  $habitat->getImage(0)->getPath() : '';
            $title = $habitat->getName();
            $text = $habitat->getDescription();

            require '../App/View/partials/_interactiveCard.php' ?>
        <?php     }
        } ?>
      </ul>

      <?php
      $title = "D'autres habitats à découvrir";
      $path = '/habitats';
      $textBtn = 'Voir les habitats';
      require '../App/View/partials/_buttonCard.php' ?>


      <img class="section__img image image--bottom" src="<?= ROOT ?>/assets/images/88484611.webp" alt="">

    </section>



    <section class="section" id="testimonial">
      <h2 class="section__title">
        Les Avis
      </h2>



      <div class="testimonial__container">
        <?php if (isset($data['advice'])) { ?>
          <div class="testimonial__hidden">

            <button id="left-btn" disabled class="button button--cube" type="button">
              <img src="<?= ROOT ?>/assets/images/icons/chevron-left.svg" alt="">
            </button>
          </div>
          <div id="testimonial-card" class="testimonial__card">
            <p id="testimonial-text" class="testimonial__text"><?= $data['advice'][0]->getAdvice() ?></p>
            <hr>
            <p id="testimonial-pseudo" class="testimonial__pseudo"><?= $data['advice'][0]->getPseudo() ?></p>
          </div>
          <div class="testimonial__hidden ">
            <button disabled id="right-btn" class="button  button--cube" type="button">
              <img src="<?= ROOT ?>/assets/images/icons/chevron-left.svg" alt="">
            </button>
          </div>
        <?php  } ?>
      </div>

      <?php
      $title = "Laissez-nous votre avis";
      $path = '#';
      $textBtn = 'Laisser un commentaire';
      require '../App/View/partials/_buttonCard.php' ?>
    </section>

    <section class="section" id="schedule">
      <h2 class="section__title">
        Horaires d'ouverture </h2>

      <?php require_once '../App/View/partials/_schedule.php' ?>
    </section>
  </main>
  <script src="<?= ROOT ?>/assets/scripts/testimonial/testimonialShow.js"></script>
  <script src="<?= ROOT ?>/assets/scripts/menu.js"></script>
</body>

</html>