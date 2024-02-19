<div <?= $autoplay ?  'autoplay'  : '' ?> class="carousel">
  <div id="carousel-select" class="carousel__select">
  </div>
  <div class="carousel__container">
    <?php
    foreach ($images as $image) { ?>
      <div class="carousel__item">
        <img src="<?= ROOT ?>/assets/images/<?= $image ?>" alt="">
      </div>
    <?php } ?>
  </div>
</div>