<?php
// need carousel.js.
?>

<div <?= $autoplay ?  'autoplay'  : '' ?> class="carousel">
  <?php if (count($images) > 1) { ?>
    <div id="carousel-select" class="carousel__select">
    </div>
  <?php } ?>
  <div class="carousel__container">
    <?php
    foreach ($images as $image) { ?>
      <div class="carousel__item">
        <img src="<?= ROOT ?>/uploads/<?= $image ?>" alt="">
      </div>
    <?php } ?>
  </div>
</div>